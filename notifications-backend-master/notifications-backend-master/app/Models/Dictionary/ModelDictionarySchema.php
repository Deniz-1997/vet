<?php

namespace App\Models\Dictionary;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelDictionarySchema
 * 
 * Схема со словарями
 *
 * @package App\Models\Dictionary
 * @method static Builder|ModelDictionarySchema newModelQuery()
 * @method static Builder|ModelDictionarySchema newQuery()
 * @method static Builder|ModelDictionarySchema query()
 * @mixin Eloquent
 */
class ModelDictionarySchema extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'dictionary';
}
