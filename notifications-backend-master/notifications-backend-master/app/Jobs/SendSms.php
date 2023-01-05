<?php

namespace App\Jobs;

use App\Components\SmsComponents;
use App\Logger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendSms
 * @package App\Jobs
 */
class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $phone;

    /**
     * Create a new job instance.
     *
     * @param string $text
     * @param string $phone
     */
    public function __construct(string $text, string $phone)
    {
        $this->text = $text;

        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $smsru = new SmsComponents(env('SMS_RU_API_TOKEN'));

        // $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
        // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
        // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
        // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему
        $sms = $smsru->send(collect([
            'from' => 'MART INFO',
            'to' => $this->phone,
            'text' => $this->text
        ]));

        if ($sms->status !== 'OK') {
            Logger::error("Error send sms for {$this->phone} with text {$this->text}. Data: " . json_encode($sms));
        }
    }
}
