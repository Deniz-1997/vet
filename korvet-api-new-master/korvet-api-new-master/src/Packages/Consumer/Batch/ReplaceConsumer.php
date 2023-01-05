<?php

namespace App\Packages\Consumer\Batch;

use App\Packages\DTO\DTOFactory;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use App\Packages\AMQP\Exception\AmqpFatalException;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Router\Route;
use App\Packages\Consumer\AbstractCrudConsumer;
use App\Packages\EventDispatcher\AMQP\AmqpBeforeBatchProcess;
use App\Service\CRUD\ReplaceItemService;
use Throwable;

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
     * Create or replace (full update) entity
     * @param Packet $packet
     *
     * @return void
     * @throws ReflectionException
     */
    public function doProcess(Packet $packet)
    {
        $request = new Request();
        $request->setMethod('PATCH');
        $this->replaceItemService->setRequest($request);

        $event = new AmqpBeforeBatchProcess($packet, __CLASS__);
        $this->replaceItemService->getDependencies()->getDispatcher()->dispatch($event);

        $data = $packet->getData();
        $this->entityClass = $data['entity'];
        if ($this->topicName !== $data['action']) {
            $this->reject();
            true;
        }

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
        $response = [];
        foreach ($data['package'] as $index => $package) {
            try {
                $responseItem = $this->replaceItemService->replace($package['id'], json_encode($package), $entityClass);
                $response[] = [
                    'status' => $responseItem->isStatusOk(),
                    'response' => $this->createSuccessResponse($responseItem),
                    'errors' => [],
                ];
            } catch (Exception | Throwable $exception) {
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
