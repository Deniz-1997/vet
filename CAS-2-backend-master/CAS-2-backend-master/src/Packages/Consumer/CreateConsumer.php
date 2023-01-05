<?php


namespace App\Packages\Consumer;

use App\Service\SerializeService;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Router\Route;
use App\Packages\EventDispatcher\AMQP\AmqpBeforeProcess;
use App\Service\CRUD\AddItemService;

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
    private $entityManager;

    /**
     * @var AddItemService
     */
    private $addItemManager;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

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
     * @required
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Packet $packet
     * @return boolean
     * @throws \Exception
     */
    public function doProcess(Packet $packet)
    {
        $request = new Request();
        $request->setMethod('POST');
        $this->addItemManager->setRequest($request);

        $event = new AmqpBeforeProcess($packet, __CLASS__);
        $this->addItemManager->getDependencies()->getDispatcher()->dispatch(AmqpBeforeProcess::AMQP_EVENT_BEFORE_PROCESS, $event);

        $creationData = $packet->getData();

        try {
            $this->entityManager->getMetadataFactory()->getMetadataFor($this->entityClass);
            $isEntity = true;
        } catch (MappingException $mappingException) {
            $isEntity = false;
        }

        if (!$isEntity) {
            $this->addItemManager->setDtoClass($this->entityClass);
        }

        $response = $this->addItemManager->add(
            json_encode($creationData),
            $this->entityClass
        );

        if (array_filter($this->getReplyData($packet))) {
            $this->reply(json_decode($response->toJsonResponse(), true));
        }
        $this->entityManager->clear();
        gc_collect_cycles();

        return true;
    }
}
