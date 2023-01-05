<?php

namespace App\Models\Channels;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class ModelChannelsAdministrators
 * 
 * Список администраторов канала
 *
 * @package App\Models\Channels
 * @property int $id
 * @property int $channel_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ModelChannels $channel
 * @property-read User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators newQuery()
 * @method static Builder|ModelChannelsAdministrators onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsAdministrators whereUserId($value)
 * @method static Builder|ModelChannelsAdministrators withTrashed()
 * @method static Builder|ModelChannelsAdministrators withoutTrashed()
 * @mixin Eloquent
 */
class ModelChannelsAdministrators extends ModelChannelsSchema
{
    use SoftDeletes;

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
        return $this->hasOne(ModelChannels::class, 'id', 'channel_id');
    }

    /**
     * Возвращаем пользователя записи
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        $this->connection = '';
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
