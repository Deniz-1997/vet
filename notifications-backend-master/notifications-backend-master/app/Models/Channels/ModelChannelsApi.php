<?php

namespace App\Models\Channels;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class ModelChannels
 * 
 * Апи токены для каналов
 *
 * @package App\Models\Channels
 * @property int $id
 * @property int $channel_id
 * @property string $api_token
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ModelChannels $channel
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi newQuery()
 * @method static Builder|ModelChannelsApi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelChannelsApi whereUpdatedAt($value)
 * @method static Builder|ModelChannelsApi withTrashed()
 * @method static Builder|ModelChannelsApi withoutTrashed()
 * @mixin Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Channels\ModelChannelsEvent[] $events
 * @property-read int|null $events_count
 */
class ModelChannelsApi extends ModelChannelsSchema
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['channel_id', 'api_token', 'name'];

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
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(ModelChannelsEvent::class, 'channel_id', 'channel_id')->with(['event']);
    }
}
