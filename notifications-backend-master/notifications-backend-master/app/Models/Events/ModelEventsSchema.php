<?php

namespace App\Models\Events;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelEventsSchema
 * 
 * Схема c событиями
 *
 * @package App\Models\Events
 * @method static Builder|ModelEventsSchema newModelQuery()
 * @method static Builder|ModelEventsSchema newQuery()
 * @method static Builder|ModelEventsSchema query()
 * @mixin Eloquent
 */
class ModelEventsSchema extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'events';
}
