<?php

namespace App\Modules\Templates;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class TemplatesListModule
 * @package App\Modules\Templates
 */
class TemplatesListModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'string', 'max:255'],
        'text' => ['string'],
        'color' => ['required', 'string', 'max:50'],
        'format_date' => ['string', 'max:30'],
        'show_status_notify' => ['required', 'boolean'],
        'show_date' => ['required', 'boolean'],
        'show_time' => ['required', 'boolean'],
    ];
}
