<?php

namespace App\Modules\Histories;

use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class HistoriesListModule
 * @package App\Modules\Histories
 */
class HistoriesListModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'history_id' => ['required', 'numeric', 'exists:dictionary.model_dictionary_histories_types,id'],
        'user_id' => ['required', 'numeric', 'exists:users,id'],
        'data' => ['required', 'array']
    ];
}
