<?php

namespace App\Models\Templates;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelEventsSchema
 * 
 * Схема c шаблонами
 *
 * @package App\Models\Templates
 * @method static Builder|ModelTemplatesSchema newModelQuery()
 * @method static Builder|ModelTemplatesSchema newQuery()
 * @method static Builder|ModelTemplatesSchema query()
 * @mixin Eloquent
 */
class ModelTemplatesSchema extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'templates';
}
