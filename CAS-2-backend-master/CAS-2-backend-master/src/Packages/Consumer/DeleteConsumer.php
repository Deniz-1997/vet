<?php


namespace App\Packages\Consumer;

use App\Packages\DTO\DTOFactory;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\AMQP\Exception\AmqpFatalException;
use App\Packages\AMQP\Exception\AmqpNotFoundEntityException;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Router\Route;
use App\Packages\EventDispatcher\AMQP\AmqpBeforeProcess;
use App\Service\CRUD\DeleteItemService;

/**
 * Class DeleteConsumer
 */
class DeleteConsumer extends AbstractCrudConsumer
{
    /**
     * @var string
     */
    protected $topicName = Route::TOPIC_DELETE_PREFIX;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var DeleteItemService
     */
    protected DeleteItemService $deleteItemService;

    /**
     * @var DTOFactory
     */
    private DTOFactory $dtoFactory;

    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @required
     * @param DTOFactory $dtoFactory
     * @return $this
     */
    public function setDtoFactory(DTOFactory $dtoFactory): self
    {
        $this->dtoFactory = $dtoFactory;

        return $this;
    }

    /**
     * @required
     * @param DeleteItemService $deleteItemService
     */
    public function setDeleteItemService(DeleteItemService $deleteItemService)
    {
        $this->deleteItemService = $deleteItemService;
    }

    /**
     * @param Packet $packet
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function doProcess(Packet $packet)
    {
        $request = new Request();
        $request->setMethod('DELETE');
        $this->deleteItemService->setRequest($request);

        $event = new AmqpBeforeProcess($packet, __CLASS__);
        $this->deleteItemService->getDependencies()->getDispatcher()->dispatch(AmqpBeforeProcess::AMQP_EVENT_BEFORE_PROCESS, $event);

        try {
            $this->entityManager->getMetadataFactory()->getMetadataFor($this->entityClass);
            $isEntity = true;
        } catch (MappingException $mappingException) {
            $isEntity = false;
        }

        if (!$isEntity) {
            $this->deleteItemService->setDtoClass($this->entityClass);

            $entityClassForDto = $this->dtoFactory->getEntityClassForDto($this->entityClass);
            if (!$entityClassForDto) {
                throw new AmqpFatalException('DTO class '.$this->entityClass.' must use DTO annotation');
            }

            $entityClass = $entityClassForDto;
        } else {
            $entityClass = $this->entityClass;
        }

        $entityId = $packet->getField('id');
        $entity = $this->entityManager->find($entityClass, $entityId);
        if (!$entity) {
            throw (new AmqpNotFoundEntityException('Entity ('.$entityClass.') by id='.$entityId.' not found', 'Error_404', null, Response::HTTP_NOT_FOUND))
                ->setEntityId($entityId);
        }

        $this->deleteItemService->deleteEntity($entity);

        $this->entityManager->clear();
        gc_collect_cycles();
    }
}
