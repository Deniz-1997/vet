<?php


namespace App\Channel;

use App\Logger;
use App\Models\User\ModelUserDevices;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Pushok\Response;

class ApnChannel extends \NotificationChannels\Apn\ApnChannel
{
    /**
     * @var string
     */
    private $token;

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $this->token = (array)$notifiable->routeNotificationFor('apn', $notification);

        if (empty($this->token)) {
            return;
        }

        $message = $notification->toApn($notifiable);

        $client = $message->client ?? $this->factory->instance();

        $responses = $this->sendNotifications($client, $message, $this->token);

        $this->dispatchEvents($notifiable, $notification, $responses);
    }

    /**
     * Dispatch failed events for notifications that weren't delivered.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param array $responses
     * @return void
     */
    protected function dispatchEvents($notifiable, $notification, array $responses)
    {
        foreach ($responses as $response) {
            if ($response->getStatusCode() === Response::APNS_SUCCESS) {
                Logger::alert('Success send notifications in ' . $this->token[0] . ' IOS');
                continue;
            }

            $event = new NotificationFailed($notifiable, $notification, static::class, [
                'id' => $response->getApnsId(),
                'token' => $response->getDeviceToken(),
                'error' => $response->getErrorReason(),
            ]);

            Logger::error("Token is not valid " . $this->token[0] . ". Message: " . $response->getErrorReason());

            if ($response->getErrorReason() !== "InvalidProviderToken") {
                ModelUserDevices::whereToken($this->token)->delete();
            }

            $this->events->dispatch($event);
        }
    }
}
