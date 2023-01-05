<?php

namespace App;

use App\Models\User\ModelUserNotificationOptions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * App\ModelUserTokenByDevice
 *
 * @property int $id
 * @property int $user_id
 * @property string $device_id
 * @property string|null $api_token
 * @property int|null $rate_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User\ModelUserNotificationOptions $notificationOptions
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ModelUserTokenByDevice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereRateLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ModelUserTokenByDevice whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ModelUserTokenByDevice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ModelUserTokenByDevice withoutTrashed()
 * @mixin \Eloquent
 */
class ModelUserTokenByDevice extends Model implements Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'device_id'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->api_token = Str::random(60);

            $item->rate_limit = 360;
        });
    }

    /**
     * @return User
     */
    public function originalUser(): User
    {
        return User::find($this->user_id);
    }

    /**
     * @return HasOne
     */
    public function notificationOptions(): HasOne
    {
        return $this->hasOne(ModelUserNotificationOptions::class, 'device_id', 'id');
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * @inheritDoc
     */
    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    /**
     * @inheritDoc
     */
    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    /**
     * @inheritDoc
     */
    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    /**
     * @inheritDoc
     */
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }
}
