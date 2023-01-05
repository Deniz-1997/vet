<?php

namespace App\Packages\Consumer;

use App\Packages\DTO\DTOFactory;
use App\Service\HandlerException\Validation\ValidationException;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use App\Packages\AMQP\Exception\AmqpFatalException;
use App\Packages\AMQP\Exception\AmqpNotFoundEntityException;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Router\Route;
use App\Packages\EventDispatcher\AMQP\AmqpBeforeProcess;
use App\Service\CRUD\ReplaceItemService;
use App\Exception\ApiException;

class ReplaceConsumer extends AbstractCrudConsumer
{
    /**
     * @var string
     */
    protected $topicName = Route::TOPIC_REPLACE_PREFIX;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ReplaceItemService
     */
    private ReplaceItemService $replaceItemService;

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
     * @param ReplaceItemService $replaceItemService
     */
    public function setReplaceItemService(ReplaceItemService $replaceItemService): void
    {
        $this->replaceItemService = $replaceItemService;
    }

    /**
     * @param Packet $packet
     *
     * @return void
     * @throws ApiException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function doProcess(Packet $packet)
    {
        $request = new Request();
        $request->setMethod('PATCH');
        $this->replaceItemService->setRequest($request);

        $event = new AmqpBeforeProcess($packet, __CLASS__);
        $this->replaceItemService->getDependencies()->getDispatcher()->dispatch($event,AmqpBeforeProcess::AMQP_EVENT_BEFORE_PROCESS);

        $entityId = $packet->getField('id');
        $updateData = $packet->getData();

        try {
            $this->entityManager->getMetadataFactory()->getMetadataFor($this->entityClass);
            $isEntity = true;
        } catch (MappingException $mappingException) {
            $isEntity = false;
        }

        if (!$isEntity) {
            $this->replaceItemService->setDtoClass($this->entityClass);

            $entityClassForDto = $this->dtoFactory->getEntityClassForDto($this->entityClass);
            if (!$entityClassForDto) {
                throw new AmqpFatalException('DTO class '.$this->entityClass.' must use DTO annotation');
            }

            $entityClass = $entityClassForDto;
        } else {
            $entityClass = $this->entityClass;
        }
        // create or replace (full update) entity
        $this->replaceItemService->replace($entityId, json_encode($updateData), $entityClass);

        $this->entityManager->clear();
        gc_collect_cycles();
    }
}
