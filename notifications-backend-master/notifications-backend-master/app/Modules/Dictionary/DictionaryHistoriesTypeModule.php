<?php

namespace App\Modules\Dictionary;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class DictionaryHistoriesTypeModule
 * @package App\Modules\Dictionary
 */
class DictionaryHistoriesTypeModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:dictionary.model_dictionary_histories_types'],
        'template' => ['required', 'string', 'unique:dictionary.model_dictionary_histories_types'],
    ];
}
