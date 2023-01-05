<?php

namespace App\Models\User;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\User\ModelUserNotificationOptions
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $from_time
 * @property string|null $to_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $device_id
 * @property-read \App\Models\User\ModelUserDevices $device
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereFromTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereToTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserNotificationOptions whereUserId($value)
 * @mixin \Eloquent
 */
class ModelUserNotificationOptions extends Model
{
    const SCHEDULED = 'scheduled';
    const ENABLED = 'alwaysEnabled';
    const DISABLED = 'alwaysDisabled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'device_id', 'type', 'from_time', 'to_time'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function device(): HasOne
    {
        return $this->hasOne(ModelUserDevices::class, 'user_id', 'id');
    }

}
