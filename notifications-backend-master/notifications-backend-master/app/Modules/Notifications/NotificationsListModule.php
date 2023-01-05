<?php

namespace App\Modules\Notifications;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class NotificationsListModule
 * @package App\Modules\Notifications
 */
class NotificationsListModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'channel_id' => ['required', 'numeric', 'exists:channels.model_channels,id'],
        'name' => ['required', 'max:255'],
        'status' => ['required', 'max:255']
    ];
}
