<?php

namespace App\Modules\User;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class UsersRoleModule
 * @package App\Modules\User
 */
class UsersRoleModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [];
}
