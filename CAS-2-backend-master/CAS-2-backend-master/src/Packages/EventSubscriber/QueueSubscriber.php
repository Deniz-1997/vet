<?php

namespace App\Packages\EventSubscriber;

use App\Packages\Client\AbstractClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\EventDispatcher\GatewayConnectorEvents\EventRegisterQueue;

/**
 * Class QueueSubscriber
 */
class QueueSubscriber implements EventSubscriberInterface
{
    private $messageExchangeRepository;

    /**
     * @var Serializer
     */
    private $serializer;
    
    /**
     * QueueSubscriber constructor.
     *
     * @param SerializerInterface       $serializer
     */
    public function __construct()
    {
    }
    
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AbstractClient::EVENT_REGISTER_QUEUE => 'onRegisterQueue',
            AbstractClient::EVENT_UNREGISTER_QUEUE => 'onUnregisterQueue',
        ];
    }
    
    /**
     * @param EventRegisterQueue $eventRegisterQueue
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onRegisterQueue(EventRegisterQueue $eventRegisterQueue)
    {
        $message = $this->serializer->denormalize($eventRegisterQueue->getData(), MessageExchange::class);
        $hash = md5($message->getMethod() . $message->getRoute() . $message->getType() . (is_array($message->getOptions() ? json_encode($message->getOptions()) : $message->getOptions())));
        
        if (!$hasMessage = $this->messageExchangeRepository->findOneBy(['hash' => $hash])) {
            $message->setHash($hash);
            $message->setStatus(MessageExchange::STATUS_PENDING);
            $this->messageExchangeRepository->save($message);
        }
        
        $eventRegisterQueue->setQueueId($hasMessage ? $hasMessage->getId() : $message->getId());
    }
    
    /**
     * @param EventRegisterQueue $eventRegisterQueue
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onUnregisterQueue(EventRegisterQueue $eventRegisterQueue)
    {
        $response = $eventRegisterQueue->getResultRequest();
        if (true === $response['status']) {
            $this->messageExchangeRepository->delete($eventRegisterQueue->getQueueId());
        } else {
            /** @var MessageExchange $message */
            if ($message = $this->messageExchangeRepository->find($eventRegisterQueue->getQueueId())) {
                $counter = $message->getCounter() + 1;
                $message->setCounter($counter);
                $message->setErrorData($response['errors'] ?? []);
                $message->setStatus(MessageExchange::STATUS_ERROR);
                $this->messageExchangeRepository->save($message);
            }
        }
    }
}
