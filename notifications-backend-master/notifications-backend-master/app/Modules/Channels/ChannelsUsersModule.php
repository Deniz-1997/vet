<?php

namespace App\Modules\Channels;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class ChannelsUsersModule
 * @package App\Modules\Channels
 */
class ChannelsUsersModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'channel_id' => ['required', 'numeric', 'exists:channels.model_channels,id'],
        'user_id' => ['required', 'numeric', 'exists:users,id']
    ];
}
