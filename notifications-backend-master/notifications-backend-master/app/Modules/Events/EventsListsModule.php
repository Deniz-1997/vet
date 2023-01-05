<?php

namespace App\Modules\Events;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class EventsListsModule
 * @package App\Modules\Events
 */
class EventsListsModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'string', 'max:255'],
        'hierarchy' => ['required', 'boolean'],
    ];
}
