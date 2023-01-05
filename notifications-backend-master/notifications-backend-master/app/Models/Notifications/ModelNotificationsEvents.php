<?php

namespace App\Models\Notifications;

use App\Models\Events\ModelEventsList;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ModelNotificationsEvents
 *
 * @package App\Models\Notifications
 * @property int $id
 * @property string|null $text
 * @property int $notifications_id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Events\ModelEventsList $event
 * @property-read \App\Models\Notifications\ModelNotificationsList $notification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notifications\ModelNotificationsSend[] $sends
 * @property-read int|null $sends_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsEvents onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents whereNotificationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsEvents whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsEvents withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsEvents withoutTrashed()
 * @mixin \Eloquent
 */
class ModelNotificationsEvents extends ModelNotificationsSchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['notifications_id', 'event_id', 'text', 'good_emoji'];

    /**
     * @return HasOne
     */
    public function notification(): HasOne
    {
        return $this->hasOne(ModelNotificationsList::class, 'id', 'notifications_id');
    }

    /**
     * @return HasOne
     */
    public function event(): HasOne
    {
        return $this->hasOne(ModelEventsList::class, 'id', 'event_id')->with(['file']);
    }

    /**
     * @return HasMany
     */
    public function sends(): HasMany
    {
        return $this->hasMany(ModelNotificationsSend::class, 'notify_event_id', 'id');
    }
}
