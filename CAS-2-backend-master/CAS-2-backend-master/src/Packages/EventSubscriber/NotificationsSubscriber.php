<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Notifications\NotificationsList;
use App\Packages\EventDispatcher\EventRequest;
use App\Service\SendNotificationsService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\WebSocket\WebSocketClient;

/**
 * Class NotificationsSubscriber
 * @package App\Packages\EventSubscriber
 */
class NotificationsSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var WebSocketClient
     */
    private WebSocketClient $socketClient;

    /**
     * AccessSubscriber constructor.
     * @param WebSocketClient $socketClient
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, WebSocketClient $socketClient)
    {
        $this->socketClient = $socketClient;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'onAfterProcessAppEntityNotificationsNotificationsListPost' => 'send',
            'onAfterProcessAppEntityNotificationsNotificationsListPatch' => 'send',
            'onAfterProcessAppEntityNotificationsNotificationsListPut' => 'send',
        );
    }

    /**
     * @param EventRequest $eventRequest
     * @throws Exception
     */
    public function send(EventRequest $eventRequest)
    {
        /** @var NotificationsList $entity */
        $entity = $eventRequest->getData();
        $this->socketClient->sendNotification($entity);
    }
}
