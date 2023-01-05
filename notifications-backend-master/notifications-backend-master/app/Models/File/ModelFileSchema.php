<?php

namespace App\Models\File;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelEventsSchema
 *
 * Схема для файлов
 *
 * @package App\Models\File
 * @method static Builder|ModelFileSchema newModelQuery()
 * @method static Builder|ModelFileSchema newQuery()
 * @method static Builder|ModelFileSchema query()
 * @mixin Eloquent
 */
class ModelFileSchema extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'file';
}
