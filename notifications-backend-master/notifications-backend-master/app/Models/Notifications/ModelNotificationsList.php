<?php

namespace App\Models\Notifications;

use App\Models\Channels\ModelChannels;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ModelNotificationsList
 *
 * @package App\Models\Notifications
 * @property int $id
 * @property int $channel_id
 * @property string $name
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $icon
 * @property-read \App\Models\Channels\ModelChannels $channel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notifications\ModelNotificationsEvents[] $notifyEvent
 * @property-read int|null $notify_event_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsList onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsList withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsList withoutTrashed()
 * @mixin \Eloquent
 */
class ModelNotificationsList extends ModelNotificationsSchema
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['channel_id', 'name', 'status', 'icon', 'event_id'];

    /**
     * @return HasOne
     */
    public function channel(): HasOne
    {
        return $this->hasOne(ModelChannels::class, 'id', 'channel_id');
    }

    /**
     * @return HasOne
     */
    public function notifyEvent(): HasMany
    {
        return $this->hasMany(ModelNotificationsEvents::class, 'notifications_id', 'id')
            ->with(['event', 'sends', 'notification']);
    }
}
