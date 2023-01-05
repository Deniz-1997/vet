<?php

namespace App\Models\User;

use App\ModelUserTokenByDevice;
use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class ModelUserDevices
 *
 * Информация об девайсе который принадлежит пользователю
 *
 * @package App\Models\User
 * @property-read User $user
 * @method static Builder|ModelUserDevices newModelQuery()
 * @method static Builder|ModelUserDevices newQuery()
 * @method static Builder|ModelUserDevices query()
 * @mixin Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $device
 * @property string|null $token
 * @property string|null $pass
 * @property string|null $reg_id
 * @property string|null $access_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ModelUserDevices whereAccessKey($value)
 * @method static Builder|ModelUserDevices whereCreatedAt($value)
 * @method static Builder|ModelUserDevices whereDevice($value)
 * @method static Builder|ModelUserDevices whereId($value)
 * @method static Builder|ModelUserDevices wherePass($value)
 * @method static Builder|ModelUserDevices whereRegId($value)
 * @method static Builder|ModelUserDevices whereToken($value)
 * @method static Builder|ModelUserDevices whereUpdatedAt($value)
 * @method static Builder|ModelUserDevices whereUserId($value)
 * @property int|null $device_id
 * @property-read \App\ModelUserTokenByDevice $currentDevice
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserDevices whereDeviceId($value)
 */
class ModelUserDevices extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'device_id', 'device', 'token', 'pass', 'reg_id', 'access_key'];

    /**
     * Возвращаем пользователя к которому относится эта информация
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        $this->connection = '';
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function currentDevice(): HasOne
    {
        return $this->hasOne(ModelUserTokenByDevice::class, 'id', 'device_id')->with(['notificationOptions']);
    }
}
