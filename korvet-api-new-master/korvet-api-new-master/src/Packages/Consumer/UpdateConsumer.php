<?php

namespace App\Packages\Consumer;

use App\Packages\DTO\DTOFactory;
use App\Service\HandlerException\Validation\ValidationException;
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
use App\Service\CRUD\UpdateItemService;
use App\Exception\ApiException;

class UpdateConsumer extends AbstractCrudConsumer
{
    /**
     * @var string
     */
    protected $topicName = Route::TOPIC_UPDATE_PREFIX;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UpdateItemService
     */
    private UpdateItemService $updateItemService;

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
     * @param UpdateItemService $updateItemService
     */
    public function setUpdateItemService(UpdateItemService $updateItemService)
    {
        $this->updateItemService = $updateItemService;
    }

    /**
     * @param Packet $packet
     *
     * @return void
     * @throws ApiException
     * @throws ValidationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function doProcess(Packet $packet)
    {
        $request = new Request();
        $request->setMethod('PATCH');
        $this->updateItemService->setRequest($request);

        $event = new AmqpBeforeProcess($packet, __CLASS__);
        $this->updateItemService->getDependencies()->getDispatcher()->dispatch($event,AmqpBeforeProcess::AMQP_EVENT_BEFORE_PROCESS);

        $entityId = $packet->getField('id');
        $updateData = $packet->getData();

        try {
            $this->entityManager->getMetadataFactory()->getMetadataFor($this->entityClass);
            $isEntity = true;
        } catch (MappingException $mappingException) {
            $isEntity = false;
        }

        if (!$isEntity) {
            $this->updateItemService->setDtoClass($this->entityClass);

            $entityClassForDto = $this->dtoFactory->getEntityClassForDto($this->entityClass);
            if (!$entityClassForDto) {
                throw new AmqpFatalException('DTO class '.$this->entityClass.' must use DTO annotation');
            }

            $entityClass = $entityClassForDto;
        } else {
            $entityClass = $this->entityClass;
        }

        $entity = $this->entityManager->find($entityClass, $entityId);
        if (!$entity) {
            throw (new AmqpNotFoundEntityException('Entity ('.$entityClass.') by id='.$entityId.' not found', 'Error_404', null, Response::HTTP_NOT_FOUND))
                ->setEntityId($entityId);
        }

        $response = $this->updateItemService->update($entityId, json_encode($updateData), $entityClass);

        if (array_filter($this->getReplyData($packet))) {
            $this->reply(json_decode($response->toJsonResponse(), true));
        }

        $this->entityManager->clear();
        gc_collect_cycles();
    }
}
