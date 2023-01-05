<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramOtherException;
use Telegram\Bot\Exceptions\TelegramSDKException;

/**
 * Class JobSendNotificationsTelegram
 * @package App\Jobs
 */
class JobSendNotificationsTelegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $text;

    /**
     * @var int
     */
    private $chat_id;

    /**
     * Create a new job instance.
     *
     * @param string $text
     * @param string $token
     * @param int $chatId
     */
    public function __construct(string $text, string $token = '', $chatId = 0)
    {
        $this->delay(mt_rand(30, 120));

        $this->token = !empty($token) ? $token : env('TOKEN_BOT_TELEGRAM');

        $this->text = $text;

        $this->chat_id = $chatId === 0 ? env('CHAT_FOR_NOTIFY_ERROR') : $chatId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $api = new Api($this->token);
            $api->sendMessage([
                'chat_id' => $this->chat_id,
                'text' => $this->text
            ]);
        }catch (TelegramOtherException $exception){
            \Log::debug('error send message: '.$exception->getMessage());
        }catch (\Exception $exception){
            \Log::debug('error send message: '.$exception->getMessage());
        }
    }
}
