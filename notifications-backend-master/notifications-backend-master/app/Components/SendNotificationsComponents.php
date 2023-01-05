<?php

namespace App\Components;


use App\Jobs\SendEmail;
use App\Jobs\SendSms;
use App\Logger;
use App\Models\Channels\ModelChannels;
use App\Models\Channels\ModelChannelsNotificationsCount;
use App\Models\Notifications\ModelNotificationsEvents;
use App\Models\Notifications\ModelNotificationsSend;
use App\Models\User\ModelUserNotificationOptions;
use App\Notifications\SendTextPush;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use Log;
use Notification;

/**
 * Class SendNotificationsComponents
 * @package App\Components
 */
class SendNotificationsComponents
{
    public const NOTIFICATIONS_SENDED = 347;

    public const NOT_FOUND_USER = 158;

    public const NOT_FOUND_CHANNEL = 890;

    /**
     * Модель с информацией об отправки
     *
     * @var ModelNotificationsSend $_notificationsSendModel
     */
    public $_notificationsSendModel;

    /**
     * Модель с инфой по пользователю
     *
     * @var User $_userModel
     */
    public $_userModel;

    /**
     * @var ModelChannels $_channel
     */
    public $_channel;

    public $update = [];

    public function __construct(ModelNotificationsSend $_notificationsSendModel)
    {
        $this->_notificationsSendModel = $_notificationsSendModel;
    }

    /**
     * @return array|null
     */
    public function send(): ?array
    {
        if ($this->_notificationsSendModel->send) {
            return [
                'status' => false,
                'code' => self::NOTIFICATIONS_SENDED,
                'message' => 'Оповещение уже отправлено.'
            ];
        }

        if ($this->_notificationsSendModel->allowed_to_send) {
            return [
                'status' => false,
                'message' => 'Требуется разрешение на отправку уведомлений.'
            ];
        }

        if (is_null($this->_notificationsSendModel->notifyEvent)) {
            return [
                'status' => false,
                'code' => self::NOTIFICATIONS_SENDED,
                'message' => 'Не найдены данные для оповещения.'
            ];
        }

        $this->_userModel = $this->_notificationsSendModel->user;

        if (is_null($this->_userModel)) {
            return [
                'status' => false,
                'code' => self::NOT_FOUND_USER,
                'message' => 'Не найден пользователь для отправки оповещения.'
            ];
        }

        $this->_channel = ModelChannels::find($this->_notificationsSendModel->notifyEvent->notification->channel_id);

        if (is_null($this->_channel)) {
            return [
                'status' => false,
                'code' => self::NOT_FOUND_CHANNEL,
                'message' => 'Не найден канал для пользователя.'
            ];
        }

        $device = $this->_sendByDevice();

        $email = $this->_sendByEmail();

        $phone = $this->_sendByPhone();

        if ($device['status'] || $email['status'] || $phone['status']) {
            $this->_notificationsSendModel->notifyEvent->notification->touch();
        } else {
            DB::table('notifications.model_notifications_sends')
                ->where('id', $this->_notificationsSendModel->id)
                ->update(['error' => true]);
        }

        $this->update['send'] = true;

        if (count($this->update) > 0) {

            DB::table('notifications.model_notifications_sends')
                ->where('id', $this->_notificationsSendModel->id)
                ->update($this->update);

            $this->refreshCounter();

            return [
                'status' => true,
                'user' => $this->_userModel,
                'device' => $device,
                'email' => $email,
                'phone' => $phone,
            ];
        }

        return [
            'status' => false,
            'message' => 'Оповещение не отправлено.'
        ];
    }

    /**
     * @return array
     */
    private function _sendByDevice(): array
    {
        # если есть данные для отправки на девайсы
        if (count($this->_userModel->devices) === 0) {
            return [
                'status' => false,
                'message' => 'Error send device. Not found data (token, id)'
            ];
        }

        $devices = collect([
            'ios' => collect(),
            'android' => collect()
        ]);

        foreach ($this->_userModel->devices as $device) {
            if (!is_null($device->currentDevice) && !is_null($device->currentDevice->notificationOptions)) {
                $options = $device->currentDevice->notificationOptions;

                if ($options->type === ModelUserNotificationOptions::DISABLED) {
                    continue;
                }

                if ($options->type === ModelUserNotificationOptions::SCHEDULED) {
                    $now = Carbon::now();

                    $from_time = Carbon::parse($options->from_time);

                    $to_time = Carbon::parse($options->to_time);

                    $from_time_second = $from_time->format('H') * 3600 + $from_time->format('i') * 60;
                    $to_time_second = $to_time->format('H') * 3600 + $to_time->format('i') * 60;
                    $current_second = $now->format('H') * 3600 + $now->format('i') * 60;

                    if ($current_second > $from_time_second && $current_second < $to_time_second) {
                        $devices->get($device->device)->push($device->token);
                    }
                }

                if ($options->type === ModelUserNotificationOptions::ENABLED) {
                    $devices->get($device->device)->push($device->token);
                }
            }
        }
//        dd($devices);
        $text = trim($this->_notificationsSendModel->notifyEvent->text);
        $event_id = $this->_notificationsSendModel->notifyEvent->notifications_id;

        $badge = ModelNotificationsSend::whereUserId($this->_notificationsSendModel->user_id)
            ->whereSend(true)
            ->whereViewed(false)
            ->count();

        $badge++;

        foreach ($devices as $platform => $tokens) {
            foreach ($tokens as $token) {
                $when = now()->addSeconds(mt_rand(5, 150));
                $channel = $platform === 'ios' ? 'apn' : 'fcm';
                try {
                    Notification::route($channel, $token)
                        ->notify(
                            (new SendTextPush($token,
                                $this->_channel->id,
                                $event_id,
                                $this->_getTitle($channel),
                                mb_strimwidth($text, 0, 512, " ..."),
                                $badge
                            ))
                                ->onQueue($platform === 'ios' ? 'notifications_device_ios' : 'notifications_device_android')
                                ->delay($when)
                        );

                    $this->update['send_device'] = true;

                } catch (Exception $exception) {

                    Logger::error("Ошибка отправки оповещении на девайс $token {$exception->getMessage()}. Data: " . json_encode([
                            $token,
                            $this->_notificationsSendModel->id
                        ]));

//                    if (strpos($exception->getMessage(), 'The specified device token was bad') !== false) {
                    // TODO удалять токен
//                    } elseif (strpos($exception->getMessage(), 'token is inactive for the specified') !== false) {
                    // TODO удалять токен
//                    } else {
//                    }
                }
            }
        }

        return [
            'status' => true,
            'message' => 'Success device send'
        ];
    }

    /**
     * Отправка по почте
     *
     * @return array
     */
    private function _sendByEmail(): array
    {
        if ($this->_channel->send_email) {

            # TODO проверка лимита на отправку почты


            if (!filter_var($this->_userModel->email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'status' => false,
                    'message' => 'Invalid email format'
                ];
            }
            $text = trim($this->_notificationsSendModel->notifyEvent->text);

            try {
                SendEmail::dispatch($text, $this->_userModel)->onQueue(env('TUBE_EMAIL'))->delay(rand(5, 10));
            } catch (Exception $exception) {
                return [
                    'status' => false,
                    'message' => 'Error send email',
                    'data' => $exception->getMessage()
                ];
            }

            $this->update['send_email'] = true;

            return [
                'status' => true,
                'message' => 'Success email send!'
            ];
        }

        return [
            'status' => false,
            'message' => 'Disabled sending emails on this channel'
        ];
    }

    /**
     * Отправка смс
     *
     * @return array
     */
    private function _sendByPhone(): array
    {
        if ($this->_channel->send_sms) {

            # TODO проверка лимита на отправку почты


            $text = trim($this->_notificationsSendModel->notifyEvent->text);

            try {
                SendSms::dispatch($text, $this->_userModel->phone)->onQueue(env('TUBE_SMS'))->delay(rand(1, 5));
            } catch (Exception $exception) {
                return [
                    'status' => false,
                    'message' => 'Error send sms',
                    'data' => $exception->getMessage()
                ];
            }

            $this->update['send_sms'] = true;

            return [
                'status' => true,
                'message' => 'Success send sms!'
            ];
        }

        return [
            'status' => false,
            'message' => 'Disabled sending sms on this channel'
        ];
    }

    /**
     * @return void
     */
    private function refreshCounter(): void
    {
        $modelNotificationSend = $this->_notificationsSendModel;

        Log::error($modelNotificationSend);
        if (!is_null($modelNotificationSend)) {
            $user_id = $modelNotificationSend->user_id;

            $modelNotifyEvent = $modelNotificationSend->notifyEvent;
            Log::error($modelNotifyEvent);
            if (!is_null($modelNotifyEvent)) {
                $notifications_id = $modelNotifyEvent->notifications_id;
                Log::error($notifications_id);

                $events = ModelNotificationsEvents::whereNotificationsId($notifications_id)
                    ->leftJoin('notifications.model_notifications_sends', 'notify_event_id', '=', 'notifications.model_notifications_events.id')
                    ->where('notifications.model_notifications_sends.send', '=', true)
                    ->where('notifications.model_notifications_sends.viewed', '=', false)
                    ->where('notifications.model_notifications_sends.user_id', '=', $user_id)
                    ->get();

                $count = $events->count();
                Log::error($count);

                $model = ModelChannelsNotificationsCount::firstOrCreate([
                    'notification_id' => $notifications_id,
                    'user_id' => $this->_notificationsSendModel->user_id,
                ]);
                Log::error($model);

                $model->count = $count;
                $model->save();
                Log::error($model);

            }
        }
    }

    /**
     * Return title with emoji if notify event have good_emoji
     *
     * @return string
     */
    private function _getTitle($channel): string
    {
        $title = trim($this->_notificationsSendModel->notifyEvent->notification->name);

        if ($this->_notificationsSendModel->need_permission_to_send) {
            # 57938 https://www.iemoji.com/view/emoji/163/symbols/warning
            # 8252 https://www.iemoji.com/view/emoji/506/symbols/double-exclamation-mark
            return $channel === "apn" ? html_entity_decode('&#57938;', ENT_NOQUOTES, 'UTF-8') . " $title" : $title;
        }

        if (is_null($this->_notificationsSendModel->notifyEvent->good_emoji)) return html_entity_decode('&#9989;', ENT_NOQUOTES, 'UTF-8') . " $title";

        $emoji = html_entity_decode($this->_notificationsSendModel->notifyEvent->good_emoji ? '&#57358;' : '&#58401;', ENT_NOQUOTES, 'UTF-8');

        return $channel === "apn" ? "{$emoji} $title" : $title;
    }
}
