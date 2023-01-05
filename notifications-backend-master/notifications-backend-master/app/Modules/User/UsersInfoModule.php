<?php

namespace App\Modules\Channels;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class UsersInfoModule
 * @package App\Modules\User
 */
class UsersInfoModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'user_id' => ['required', 'numeric', 'unique:users'],
        'group_id' => ['required', 'numeric', 'exists:dictionary.model_dictionary_group_users,id'],
    ];
}
