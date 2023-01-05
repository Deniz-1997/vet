<?php

namespace App\Models\Channels;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelChannelsSchema
 *
 * @package App\Models\Channels
 * @method static Builder|ModelChannelsSchema newModelQuery()
 * @method static Builder|ModelChannelsSchema newQuery()
 * @method static Builder|ModelChannelsSchema query()
 * @mixin Eloquent
 */
class ModelChannelsSchema extends Model
{
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'channels';
}
