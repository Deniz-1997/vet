<?php

namespace App\Modules\User;

use App\Components\SmsComponents;
use App\Models\User\ModelUserSmsCode;
use App\Modules\Module;
use App\Modules\TraitModule;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

/**
 * Class UsersSmsCodesModule
 * @package App\Modules\User
 */
class UsersSmsCodesModule extends Module
{
    use TraitModule;
    
    /**
     * @var array
     */
    public static $rules = [
        'user_id' => ['required', 'exists:users,id'],
        'device_id' => ['required', 'exists:model_user_token_by_devices,id'],
    ];

    /**
     * @param array $array
     * @return mixed
     * @throws Exception
     */
    public static function create(array $array)
    {
        $collect = collect($array);

        static::validation($array);

        $code = rand(1000, 9999);

        static::refreshCompleted($collect->get('user_id'));

        $model = static::getModel()->create([
            'user_id' => $collect->get('user_id'),
            'device_id' => $collect->get('device_id'),
            'hash' => Str::random(60),
            'code' => !is_null($collect->get('phone')) ? $code : 1234
        ]);

        $smsru = new SmsComponents(env('SMS_RU_API_TOKEN'));

        if (!is_null($collect->get('phone'))) {
            $sms = $smsru->send((object)[
                'from' => 'MART INFO',
                'to' => $collect->get('phone'),
                'text' => $code
            ]);

            if ($sms->status !== 'OK') {
                throw new Exception('Error send sms to ' . $collect->get('phone') . ' User ID: ' . $collect->get('user_id'));
            }
        }

        static::setModel($model);

        return new static();
    }

    /**
     * @param int $user_id
     * @param int $device_id
     * @return bool
     */
    public static function checkLimit(int $user_id, int $device_id): bool
    {
        $record = static::getModel()->whereCompleted(false)
            ->whereDeviceId($device_id)
            ->whereUserId($user_id)->latest()->first();

        if (is_null($record)) {
            return true;
        }

        $date = Carbon::now();

        $date_record = Carbon::parse($record->created_at);

        if ($date->timestamp - $date_record->timestamp >= 60 * 5) {
            return true;
        }

        return false;
    }

    /**
     * @param int $user_id
     */
    public static function refreshCompleted(int $user_id)
    {
        $model = ModelUserSmsCode::whereUserId($user_id)->get();

        foreach ($model as $item) {
            $item->completed = true;
            $item->save();
        }
    }

    /**
     * @param int $user_id
     * @param int $device_id
     */
    public static function completedRecords(int $user_id, int $device_id): void
    {
        $records = static::getModel()->whereCompleted(false)
            ->whereDeviceId($device_id)
            ->whereUserId($user_id)
            ->get();

        foreach ($records as $record) {
            $record->completed = true;
            $record->save();
        }

    }
}
