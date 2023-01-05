<?php

namespace App\Models\Notifications;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelNotificationsSchema
 *
 * @package App\Models\Notifications
 * @method static Builder|ModelNotificationsSchema newModelQuery()
 * @method static Builder|ModelNotificationsSchema newQuery()
 * @method static Builder|ModelNotificationsSchema query()
 * @mixin Eloquent
 */
class ModelNotificationsSchema extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'notifications';
}
