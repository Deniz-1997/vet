<?php

namespace App\Service;

use App\Entity\Notifications\NotificationsList;
use App\Entity\Notifications\NotificationsToSend;
use App\Message\SendNotification;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\WebSocket\WebSocketClient;

/**
 * Class SendNotificationsService
 * @package App\Service
 */
class SendNotificationsService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var NotificationsList
     */
    public NotificationsList $notificationList;

    /**
     * @var WebSocketClient
     */
    public WebSocketClient $socketClient;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, WebSocketClient $socketClient)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->socketClient = $socketClient;
    }

    /**
     * Создаем оповещение
     *
     * @param array $a
     * @return $this
     */
    public function create(array $a): SendNotificationsService
    {
        $this->notificationList = new NotificationsList();
        $this->notificationList->setType($a['type']);
        $this->notificationList->setData($a['data']);

        foreach ($a['to'] as $to) {
            $notificationSend = new NotificationsToSend();
            $notificationSend->setChannel($to['channel']);
            $notificationSend->setValue($to['id']);
            $notificationSend->setType($to['type']);
            $this->notificationList->addToSend($notificationSend);
        }

        $this->entityManager->persist($this->notificationList);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Отправляем оповещение
     *
     * @param NotificationsList|null $notificationsList
     * @return void
     * @throws Exception
     */
    public function send(NotificationsList $notificationsList = null): void
    {
         if (is_null($notificationsList) && is_null($this->notificationList)) {
            throw new Exception('Не указано какое оповещение отправлять');
        }

        $notificationsList = is_null($notificationsList) ? $this->notificationList : $notificationsList; 

        try {
            $this->bus->dispatch(new SendNotification($notificationsList->getId()));
            foreach ($notificationsList->getToSend() as $toSend) {
                if ($toSend->gettype() === 'USER' && $toSend->getViewed() == false) {
                    $this->socketClient->updateClientNotificationsList($toSend->getValue());
                }
            }
        }
        catch (Exception $ex) {
            throw $ex;
        }
    }
}
