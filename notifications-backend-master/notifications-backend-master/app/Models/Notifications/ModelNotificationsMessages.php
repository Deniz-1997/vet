<?php

namespace App\Models\Notifications;


use App\Models\Channels\ModelChannels;
use App\Models\Events\ModelEventsList;
use App\Models\Templates\ModelTemplatesList;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Notifications\ModelNotificationsMessages
 *
 * @property int $id
 * @property int $event_id
 * @property int $template_id
 * @property int $channel_id
 * @property string|null $status
 * @property string $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ModelChannels $channel
 * @property-read ModelEventsList $event
 * @property-read ModelTemplatesList $template
 * @method static Builder|ModelNotificationsMessages newModelQuery()
 * @method static Builder|ModelNotificationsMessages newQuery()
 * @method static Builder|ModelNotificationsMessages query()
 * @method static Builder|ModelNotificationsMessages whereChannelId($value)
 * @method static Builder|ModelNotificationsMessages whereCreatedAt($value)
 * @method static Builder|ModelNotificationsMessages whereEventId($value)
 * @method static Builder|ModelNotificationsMessages whereId($value)
 * @method static Builder|ModelNotificationsMessages whereStatus($value)
 * @method static Builder|ModelNotificationsMessages whereTemplateId($value)
 * @method static Builder|ModelNotificationsMessages whereText($value)
 * @method static Builder|ModelNotificationsMessages whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read \App\Models\Events\ModelEventsList $eventExternal
 * @property-read \App\Models\Templates\ModelTemplatesList $templateExternal
 */
class ModelNotificationsMessages extends ModelNotificationsSchema
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'template_id',
        'channel_id',
        'status',
        'text',
    ];

    /**
     * @return HasOne
     */
    public function event(): HasOne
    {
        return $this->hasOne(ModelEventsList::class, 'id', 'event_id');
    }

    /**
     * @return HasOne
     */
    public function template(): HasOne
    {
        return $this->hasOne(ModelTemplatesList::class, 'id', 'template_id');
    }

    /**
     * Метод для внешнего API, не нужно использовать для внутреннего
     *
     * @return HasOne
     */
    public function eventExternal(): HasOne
    {
        return $this->hasOne(ModelEventsList::class, 'id', 'event_id')
            ->select(['id', 'name', 'created_at']);
    }

    /**
     * Метод для внешнего API, не нужно использовать для внутреннего
     *
     * @return HasOne
     */
    public function templateExternal(): HasOne
    {
        return $this->hasOne(ModelTemplatesList::class, 'id', 'template_id')
            ->select(['id', 'text', 'color', 'format_date', 'show_status_notify', 'show_date', 'show_time']);
    }

    /**
     * @return HasOne
     */
    public function channel(): HasOne
    {
        return $this->hasOne(ModelChannels::class, 'id', 'channel_id')->with(['messages']);
    }

}
