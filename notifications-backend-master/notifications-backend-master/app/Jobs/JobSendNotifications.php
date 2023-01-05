<?php

namespace App\Jobs;

use App\Components\SendNotificationsComponents;
use App\Logger;
use App\Models\Notifications\ModelNotificationsSend;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobSendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ModelNotificationsSend
     */
    public $model;

    /**
     * Create a new job instance.
     *
     * @param ModelNotificationsSend $modelNotificationsSend
     */
    public function __construct(ModelNotificationsSend $modelNotificationsSend)
    {
        $this->model = $modelNotificationsSend;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new SendNotificationsComponents($this->model))->send();
    }
}
