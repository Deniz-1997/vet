<?php


namespace App\Packages\Consumer\Batch;

use App\Exception\AmqpConsumerEntityIsEmptyException;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Router\Route;
use App\Packages\Consumer\AbstractCrudConsumer;
use App\Packages\EventDispatcher\AMQP\AmqpBeforeBatchProcess;
use App\Service\CRUD\AddItemService;
use App\Packages\Response\BaseResponse;
use Throwable;

/**
 * Class CreateConsumer
 */
class CreateConsumer extends AbstractCrudConsumer
{

    /**
     * @var string
     */
    protected $topicName = Route::TOPIC_CREATE_PREFIX;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var AddItemService
     */
    private AddItemService $addItemManager;

    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager (EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @required
     * @param AddItemService $addItemManager
     */
    public function setAddItemManager (AddItemService $addItemManager)
    {
        $this->addItemManager = $addItemManager;
    }

    /**
     * @param Packet $packet
     * @return boolean
     * @throws Exception
     */
    public function doProcess(Packet $packet)
    {
        $request = new Request();
        $request->setMethod('POST');
        $this->addItemManager->setRequest($request);

        $event = new AmqpBeforeBatchProcess($packet, __CLASS__);
        $this->addItemManager->getDependencies()->getDispatcher()->dispatch($event);

        $creationData = $packet->getData();
        $this->entityClass = $creationData['entity'];
        if ($this->topicName !== $creationData['action']) {
            $this->reject();

            return;
        }
        if ($this->validate($creationData)) {
            try {
                $this->entityManager->getMetadataFactory()->getMetadataFor($this->entityClass);
                $isEntity = true;
            } catch (MappingException $mappingException) {
                $isEntity = false;
            }

            if (!$isEntity) {
                $this->addItemManager->setDtoClass($this->entityClass);
            }
            $response = [];
            foreach ($creationData['package'] as $index => $package) {
                try {
                    $responseItem = $this->addItemManager->add(json_encode($package), $this->entityClass);
                    $response[] = [
                        'status' => $responseItem->isStatusOk(),
                        'response' => $this->createSuccessResponse($responseItem),
                        'errors' => null
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

            return true;
        }
    }

    /**
     * @param array $creationData
     *
     * @return bool
     * @throws AmqpConsumerEntityIsEmptyException
     */
    private function validate(array $creationData):bool
    {
        if (!$this->entityClass = $creationData['entity']) {
            throw new AmqpConsumerEntityIsEmptyException('amqp.exception.entity.is_null');
        }
        if ($this->topicName !== $creationData['action']) {
            $this->reject();

            return false;
        }
        if (!$creationData['package']) {
            $this->ack();

            return false;
        }

        return true;
    }
}
