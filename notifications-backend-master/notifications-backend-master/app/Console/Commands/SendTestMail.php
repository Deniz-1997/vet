<?php

namespace App\Console\Commands;

use App\Jobs\SendEmail;
use App\Mail\SendNotifications;
use App\User;
use Illuminate\Console\Command;
use Mail;

class SendTestMail extends Command
{
    protected $signature = 'send:mail';

    protected $description = 'Тестовая отправки писем';

    public function handle()
    {
        $this->info('Send email by queue');
        SendEmail::dispatch('test', User::withTrashed()->whereId(16)->first())->onQueue(env('TUBE_EMAIL'))->delay(rand(1, 10));

        $this->info('Send email no queue');
        Mail::to('bred1996@ya.ru')->send(new SendNotifications('test'));
    }
}
