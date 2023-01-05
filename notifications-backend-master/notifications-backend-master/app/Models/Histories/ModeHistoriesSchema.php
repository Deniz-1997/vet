<?php

namespace App\Models\Histories;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModeHistoriesSchema
 *
 * @package App\Models\Histories
 * @method static Builder|ModeHistoriesSchema newModelQuery()
 * @method static Builder|ModeHistoriesSchema newQuery()
 * @method static Builder|ModeHistoriesSchema query()
 * @mixin Eloquent
 */
class ModeHistoriesSchema extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'histories';
}
