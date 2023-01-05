<?php

namespace App\Console\Commands;

use App\Components\SmsComponents;
use Illuminate\Console\Command;

class SendTestSms extends Command
{
    protected $signature = 'send:sms {phone}';

    protected $description = 'Тестовая отправки смс';

    public function handle()
    {
        $smsru = new SmsComponents(env('SMS_RU_API_TOKEN'));

        $sms = $smsru->send((object)[
            'from' => 'MART INFO',
            'to' => $this->argument('phone'),
            'text' => 'Тестовое оповещение'
        ]);

        dd('response: ', $sms);
    }
}
