<?php

namespace App\Models\Notifications;

use App\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ModelNotificationsPermissions
 * @package App\Models\Notifications
 */
class ModelNotificationsPermissions extends ModelNotificationsSchema
{

    protected $fillable = [
        'created_user_id',
        'notification_id',
        'allow_send'
    ];

    /**
     * @return HasOne
     */
    public function notification()
    {
        return $this->hasOne(ModelNotificationsSend::class, 'id', 'notification_id');
    }

    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_user_id');
    }
}
