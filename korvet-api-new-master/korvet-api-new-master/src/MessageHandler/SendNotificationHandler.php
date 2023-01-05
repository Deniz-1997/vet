<?php

namespace App\MessageHandler;

use App\Entity\Notifications\NotificationsToSend;
use App\Entity\Owner;
use App\Entity\Reference\Notifications\ReferenceNotificationsChannel;
use App\Entity\User\User;
use App\Message\SendNotification;
use App\Repository\Notifications\NotificationsListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class SendNotificationHandler
 *
 * Обработка оповещений для пользователей или владельцев, обрабатывается командой php bin/console messenger:consume
 *
 * @package App\MessageHandler
 */
final class SendNotificationHandler implements MessageHandlerInterface
{
    /**
     * @var NotificationsListRepository
     */
    private NotificationsListRepository $notificationsListRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entity;

    public function __construct(NotificationsListRepository $notificationsListRepository, EntityManagerInterface $entityManager)
    {
        $this->notificationsListRepository = $notificationsListRepository;
        $this->entity = $entityManager;

    }

    public function __invoke(SendNotification $notification)
    {
        $notificationEntity = $this->notificationsListRepository->find($notification->getNotifyId());

        $sended = $notificationEntity->getToSend();

        foreach ($sended as $item) {
            $class = $item->getType() === NotificationsToSend::USER_TYPE ? User::class : Owner::class;

            // TODO сделать проверку, которая будет проверять настройки пользователя, какие ему пуши можно слать и куда, ну так-как пока только 1 пуш, он обязательный и его нельзя не выбрать
            $value = $this->entity->find($class, $item->getValue());


            if (is_null($item->getSendedAt())) {
                switch ($item->getChannel()->getId()) {
                    case ReferenceNotificationsChannel::KORVET: // TODO сделать отправку в вебсокет, что появился новое оповещение

                        break;
                }

                $item->setSendedAt(new \DateTime());
            }
            $this->entity->persist($item);
        }

        $this->entity->flush();
    }
}
