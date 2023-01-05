<?php

namespace App\Modules\Channels;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class ChannelsEventModule
 * @package App\Modules\Channels
 */
class ChannelsEventModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'channel_id' => [
            'required', 'numeric',
            'exists:channels.model_channels,id'
        ],
        'event_id' => ['required', 'numeric', 'exists:events.model_events_lists,id']
    ];
}
