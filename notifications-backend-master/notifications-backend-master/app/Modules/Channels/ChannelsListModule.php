<?php

namespace App\Modules\Channels;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class ChannelsListModule
 * @package App\Modules\Channels
 */
class ChannelsListModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'sms_limit' => ['required', 'numeric'],
        'email_limit' => ['required', 'numeric'],
        'name' => ['required', 'string', 'max:255', 'unique:channels.model_channels'],
        'send_sms' => ['required', 'bool'],
        'send_email' => ['required', 'bool'],
    ];
}
