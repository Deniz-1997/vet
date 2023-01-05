<?php

namespace App\Packages\Consumer\Batch;

use App\Packages\DTO\DTOFactory;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\AMQP\Exception\AmqpFatalException;
use App\Packages\AMQP\Exception\AmqpNotFoundEntityException;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Router\Route;
use App\Packages\Consumer\AbstractCrudConsumer;
use App\Packages\EventDispatcher\AMQP\AmqpBeforeBatchProcess;
use App\Service\CRUD\UpdateItemService;

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
     * @throws ReflectionException
     * @throws Exception
     */
    public function doProcess(Packet $packet)
    {
        $request = new Request();
        $request->setMethod('PATCH');
        $this->updateItemService->setRequest($request);

        $event = new AmqpBeforeBatchProcess($packet, __CLASS__);
        $this->updateItemService->getDependencies()->getDispatcher()->dispatch($event);

        $updateData = $packet->getData();
        $this->entityClass = $updateData['entity'];
        if ($this->topicName !== $updateData['action']) {
            $this->reject();

            return;
        }

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
        $response = [];
        foreach ($updateData['package'] as $index => $package) {
            $entityId = $package['id'];
            $entity = $this->entityManager->find($entityClass, $entityId);
            try {
                if (!$entity) {
                    throw (new AmqpNotFoundEntityException('Entity ('.$entityClass.') by id='.$entityId.' not found', 'Error_404', null, Response::HTTP_NOT_FOUND))
                        ->setEntityId($entityId);
                }
                $responseItem = $this->updateItemService->update($entityId, json_encode($package), $entityClass);
                $response[] = [
                    'status' => $responseItem->isStatusOk(),
                    'response' => $this->createSuccessResponse($responseItem),
                    'errors' => [],
                ];
            } catch (Exception | \Throwable $exception) {
                $response[] = $this->createResponseWitnException($package, $index, $exception);
            }
        }

        if (array_filter($this->getReplyData($packet))) {
            $this->reply($response);
            $packet->getAMQPMessage()->set('reply_to', null);
        }

        $this->entityManager->clear();
        gc_collect_cycles();
    }
}
