<?php

namespace App\Console\Commands;

use App\Notifications\SendTextPush;
use Illuminate\Console\Command;
use Notification;

class SendTestPush extends Command
{
    protected $signature = 'send:push {token} {event_id} {channel_id} {title} {body} {protocol} {queue?}';

    protected $description = 'Тестовая отправки уведомлений';

    public function handle()
    {
        $lightning = html_entity_decode('&#57938;', ENT_NOQUOTES, 'UTF-8');
        $message = "{$lightning} new push";
        $when = now()->addSeconds(30);

        $push = (new SendTextPush($this->argument('token'),
            $this->argument('channel_id'),
            $this->argument('event_id'),
            $message,
            $this->argument('body'),
            mt_rand(10, 1000)
        ));

        if ($this->argument('queue')) {
            $push->onQueue($this->argument('protocol') === 'apn' ? 'notifications_device_ios' : 'notifications_device_android')->delay($when);
        }

        Notification::route($this->argument('protocol'), $this->argument('token'))
            ->notify($push);
    }
}
