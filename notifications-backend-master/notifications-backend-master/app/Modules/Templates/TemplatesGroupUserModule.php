<?php

namespace App\Modules\Templates;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class TemplatesGroupUserModule
 * @package App\Modules\Templates
 */
class TemplatesGroupUserModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'template_id' => ['required', 'numeric', 'exists:templates.model_templates_lists,id'],
        'group_id' => ['required', 'numeric', 'exists:dictionary.model_dictionary_group_users,id'],
    ];
}
