<?php

namespace App\Modules\Notifications;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class NotificationsEventsModule
 * @package App\Modules\Notifications
 */
class NotificationsEventsModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'notifications_id' => ['required', 'numeric', 'exists:notifications.model_notifications_lists,id'],
        'event_id' => ['required', 'numeric', 'exists:events.model_events_lists,id'],
    ];
}
