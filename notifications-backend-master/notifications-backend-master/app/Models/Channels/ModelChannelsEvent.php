<?php

namespace App\Models\Channels;

use App\Models\Events\ModelEventsList;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ModelChannelsEvent
 *
 * @package App\Models\Channels
 * @property int $id
 * @property int $event_id
 * @property int $channel_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Channels\ModelChannels $channel
 * @property-read \App\Models\Events\ModelEventsList $event
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannelsEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModelChannelsEvent extends ModelChannelsSchema
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'channel_id'];

    /**
     * Возвращаем события оповещения
     *
     * @return HasOne
     */
    public function event():HasOne
    {
        return $this->hasOne(ModelEventsList::class, 'id', 'event_id');
    }

    /**
     * Возвращаем канал оповещения
     *
     * @return HasOne
     */
    public function channel(): HasOne
    {
        return $this->hasOne(ModelChannels::class, 'id', 'channel_id');
    }
}
