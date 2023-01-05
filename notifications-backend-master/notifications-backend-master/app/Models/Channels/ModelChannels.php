<?php

namespace App\Models\Channels;

use App\Models\Notifications\ModelNotificationsList;
use App\Models\Notifications\ModelNotificationsMessages;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class ModelChannels
 * 
 * Каналы оповещений
 *
 * @package App\Models\Channels
 * @property int $id
 * @property int $sms_limit
 * @property string $name
 * @property bool $send_sms
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|ModelChannels newModelQuery()
 * @method static Builder|ModelChannels newQuery()
 * @method static Builder|ModelChannels query()
 * @method static Builder|ModelChannels whereCreatedAt($value)
 * @method static Builder|ModelChannels whereDeletedAt($value)
 * @method static Builder|ModelChannels whereId($value)
 * @method static Builder|ModelChannels whereName($value)
 * @method static Builder|ModelChannels whereSendSms($value)
 * @method static Builder|ModelChannels whereSmsLimit($value)
 * @method static Builder|ModelChannels whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $email_limit
 * @property bool $send_email
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Channels\ModelChannelsAdministrators[] $administrators
 * @property-read int|null $administrators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Channels\ModelChannelsApi[] $apis
 * @property-read int|null $apis_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Channels\ModelChannelsEvent[] $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notifications\ModelNotificationsMessages[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notifications\ModelNotificationsList[] $notificationsList
 * @property-read int|null $notifications_list_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Channels\ModelChannelsUsers[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannels whereEmailLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Channels\ModelChannels whereSendEmail($value)
 */
class ModelChannels extends ModelChannelsSchema
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sms_limit', 'name', 'send_sms', 'email_limit', 'send_email'];

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ModelNotificationsMessages::class, 'channel_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(ModelChannelsEvent::class, 'channel_id', 'id')->with(['event']);
    }

    /**
     * @return HasMany
     */
    public function administrators(): HasMany
    {
        return $this->hasMany(ModelChannelsAdministrators::class, 'channel_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function user(): HasMany
    {
        return $this->hasMany(ModelChannelsUsers::class, 'channel_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function apis(): HasMany
    {
        return $this->hasMany(ModelChannelsApi::class, 'channel_id', 'id');
    }

    public function notificationsList(): HasMany
    {
        return $this->hasMany(ModelNotificationsList::class, 'channel_id', 'id');
    }

}
