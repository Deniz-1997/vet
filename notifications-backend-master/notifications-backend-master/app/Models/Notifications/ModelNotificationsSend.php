<?php

namespace App\Models\Notifications;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Notifications\ModelNotificationsSend
 *
 * @property int $id
 * @property int $user_id
 * @property int $messages_id
 * @property bool|null $send
 * @property Carbon $sended_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ModelNotificationsMessages $messages
 * @property-read User $user
 * @method static Builder|ModelNotificationsSend newModelQuery()
 * @method static Builder|ModelNotificationsSend newQuery()
 * @method static Builder|ModelNotificationsSend query()
 * @method static Builder|ModelNotificationsSend whereCreatedAt($value)
 * @method static Builder|ModelNotificationsSend whereId($value)
 * @method static Builder|ModelNotificationsSend whereMessagesId($value)
 * @method static Builder|ModelNotificationsSend whereSend($value)
 * @method static Builder|ModelNotificationsSend whereSendedDate($value)
 * @method static Builder|ModelNotificationsSend whereUpdatedAt($value)
 * @method static Builder|ModelNotificationsSend whereUserId($value)
 * @mixin Eloquent
 * @property int $notify_event_id
 * @property bool|null $send_email
 * @property bool|null $send_sms
 * @property bool|null $send_device
 * @property bool|null $viewed
 * @property bool|null $need_permission_to_send
 * @property bool|null $allowed_to_send
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Notifications\ModelNotificationsEvents $notifyEvent
 * @property-read \App\Models\Notifications\ModelNotificationsEvents $notifyEventWithoutWith
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsSend onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsSend whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsSend whereNotifyEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsSend whereSendDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsSend whereSendEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsSend whereSendSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\ModelNotificationsSend whereViewed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsSend withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Notifications\ModelNotificationsSend withoutTrashed()
 */
class ModelNotificationsSend extends ModelNotificationsSchema
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'notify_event_id',
        'sended_date',
        'allowed_to_send',
        'need_permission_to_send'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'sended_date'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        $this->connection = '';
        return $this->hasOne(User::class, 'id', 'user_id')->with(['devices']);
    }

    /**
     * @return HasOne
     */
    public function notifyEvent(): HasOne
    {
        return $this->hasOne(ModelNotificationsEvents::class, 'id', 'notify_event_id')
            ->with(['event', 'notification']);
    }

    /**
     * @return HasOne
     */
    public function notifyEventWithoutWith(): HasOne
    {
        return $this->hasOne(ModelNotificationsEvents::class, 'id', 'notify_event_id');
    }

    /**
     * @return HasOne
     */
    public function permissionSendNotification(): HasOne
    {
        return $this->hasOne(ModelNotificationsPermissions::class, 'notification_id', 'id');
    }
}
