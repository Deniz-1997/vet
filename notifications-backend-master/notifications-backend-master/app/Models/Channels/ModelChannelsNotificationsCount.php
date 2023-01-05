<?php

namespace App\Models\Channels;

use App\Models\Notifications\ModelNotificationsList;
use App\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ModelChannelsNotificationsCount
 * @package App\Models\Channels
 */
class ModelChannelsNotificationsCount extends ModelChannelsSchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['notification_id', 'user_id'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function notification(): HasOne
    {
        return $this->hasOne(ModelNotificationsList::class, 'id', 'notification_id');
    }

}
