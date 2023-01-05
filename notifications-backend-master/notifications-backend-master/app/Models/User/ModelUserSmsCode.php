<?php

namespace App\Models\User;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\User\ModelUserSmsCode
 *
 * @property int $id
 * @property int $user_id
 * @property string $hash
 * @property bool|null $completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $code
 * @property int|null $device_id
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserSmsCode whereUserId($value)
 * @mixin \Eloquent
 */
class ModelUserSmsCode extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'hash', 'code', 'device_id'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
