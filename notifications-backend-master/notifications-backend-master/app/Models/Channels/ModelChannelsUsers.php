<?php

namespace App\Models\Channels;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class ModelChannelsUsers
 * 
 * Список администраторов канала
 *
 * @package App\Models\Channels
 * @property int $id
 * @property int $channel_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ModelChannels $channel
 * @property-read User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers newQuery()
 * @method static Builder|ModelChannelsUsers onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsUsers whereUserId($value)
 * @method static Builder|ModelChannelsUsers withTrashed()
 * @method static Builder|ModelChannelsUsers withoutTrashed()
 * @mixin Eloquent
 */
class ModelChannelsUsers extends ModelChannelsSchema
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['channel_id', 'user_id'];

    /**
     * Возвращаем канал к которому относится токен
     *
     * @return HasOne
     */
    public function channel(): HasOne
    {
        return $this->hasOne(ModelChannels::class, 'id', 'channel_id')->with(['events', 'notificationsList']);
    }

    /**
     * Возвращаем пользователя записи
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        $this->connection = '';
        return $this->hasOne(User::class, 'id', 'user_id')->with(['organization']);
    }
}
