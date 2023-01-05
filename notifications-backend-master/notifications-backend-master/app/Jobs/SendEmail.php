<?php

namespace App\Jobs;

use App\Mail\SendNotifications;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $text;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @param string $text
     * @param User $user
     */
    public function __construct(string $text, User $user)
    {
        $this->text = $text;

        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(rand(10, 60));
        Mail::to($this->user)->send(new SendNotifications($this->text));
    }
}
