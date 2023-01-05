<?php

namespace App\Models\Dictionary;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class ModelDictionaryGroupUsers
 *
 * Словарь с группами пользователей
 *
 * @package App\Models\Dictionary
 * @property int $id
 * @property string $name
 * @property boolean $responsible_notifications
 * @property boolean $filter_notifications
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers newQuery()
 * @method static Builder|ModelDictionaryGroupUsers onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers whereDelaySend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryGroupUsers whereUpdatedAt($value)
 * @method static Builder|ModelDictionaryGroupUsers withTrashed()
 * @method static Builder|ModelDictionaryGroupUsers withoutTrashed()
 * @mixin Eloquent
 * @property int|null $main_organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryGroupUsers whereMainOrganizationId($value)
 */
class ModelDictionaryGroupUsers extends ModelDictionarySchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'channel_id', 'responsible_notifications', 'filter_notifications'];
}
