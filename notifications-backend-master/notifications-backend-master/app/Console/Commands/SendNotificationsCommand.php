<?php

namespace App\Console\Commands;

use App\Components\SendNotificationsComponents;
use App\Models\Notifications\ModelNotificationsSend;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notifications {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляем оповещения которые в очереди';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelNotificationsSends = ModelNotificationsSend::with(['user', 'notifyEvent'])
            ->get();

        $user_id = $this->argument('user_id');

        foreach ($modelNotificationsSends as $modelNotificationsSend) {
            if (Carbon::now()->timestamp >= $modelNotificationsSend->sended_date->timestamp) {
                $this->info("Send #{$modelNotificationsSend->id}");
//
                $component = (new SendNotificationsComponents($modelNotificationsSend))->send();
//
                if (!$component['status']) {
                    $this->error("Send #{$modelNotificationsSend->id}. Message: {$component['message']}");
                    continue;
                }
//
                $this->info(json_encode($component));
            }
        }
    }
}
