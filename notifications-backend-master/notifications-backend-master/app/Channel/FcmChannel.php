<?php


namespace App\Channel;

use App\Logger;
use App\Models\User\ModelUserDevices;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Messaging\CloudMessage;

class FcmChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$deviceTokens = $notifiable->routeNotificationFor('fcm', $notification)) {
            return;
        }

        $data = $notification->toFcm($notifiable);

        $messaging = app('firebase.messaging');

        $msg = CloudMessage::withTarget('token', $deviceTokens)
            ->withNotification(\Kreait\Firebase\Messaging\Notification::create($data->get('title'), $data->get('body')))
            ->withData([
                'channel_id' => $data->get('channel_id'),
                'event_id' => $data->get('event_id')
            ]);

        try {
            $r = $messaging->send($msg);
            Logger::alert('Success send notifications in ' . $deviceTokens . ' android');
        } catch (InvalidMessage | NotFound $exception) {
            // DELETE TOKEN
            // TOKEN IS NOT VALID
            ModelUserDevices::whereToken($deviceTokens)->delete();
            Logger::debug("Token is not valid $deviceTokens. Message: ".$exception->getMessage());
        }
    }
}
