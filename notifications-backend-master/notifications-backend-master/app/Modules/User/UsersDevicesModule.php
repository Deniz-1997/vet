<?php

namespace App\Modules\User;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class UsersDevicesModule
 * @package App\Modules\User
 */
class UsersDevicesModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'user_id' => ['required', 'numeric', 'unique:users'],
        'device' => ['required', 'in:android,iphone']
    ];
}
