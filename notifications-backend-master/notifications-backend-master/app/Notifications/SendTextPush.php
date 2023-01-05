<?php

namespace App\Notifications;

use App\Channel\ApnChannel;
use App\Channel\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Apn\ApnMessage;

class SendTextPush extends Notification implements ShouldQueue
{
    use Queueable;

    private $token;

    private $event_id, $channel_id;

    private $body, $title;

    private $badge;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $channel_id
     * @param $event_id
     * @param $title
     * @param $body
     * @param $badge
     */
    public function __construct($token, $channel_id, $event_id, $title, $body, $badge)
    {
        $this->token = $token;
        $this->channel_id = $channel_id;
        $this->event_id = $event_id;
        $this->title = $title;
        $this->body = $body;
        $this->badge = $badge;
    }

    public function toApn($notifiable)
    {
        return ApnMessage::create()
            ->badge($this->badge)
            ->custom('channel_id', $this->channel_id)
            ->custom('event_id', $this->event_id)
            ->title($this->title)
            ->body($this->body);
    }

    public function toFcm($notifiable)
    {
        return collect([
            'token' => $this->token,
            'body' => $this->body,
            'title' => $this->title,
            'channel_id' => $this->channel_id,
            'event_id' => $this->event_id,
        ]);
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ApnChannel::class, FcmChannel::class];
    }

    /**
     * @return mixed
     */
    public function routeNotificationForApn()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function routeNotificationForFcm()
    {
        return $this->token;
    }
}
