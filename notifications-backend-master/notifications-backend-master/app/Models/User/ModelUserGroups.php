<?php

namespace App\Models\User;

use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\User\ModelUserGroups
 *
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ModelDictionaryGroupUsers $dictionaryGroup
 * @property-read User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups newQuery()
 * @method static Builder|ModelUserGroups onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelUserGroups whereUserId($value)
 * @method static Builder|ModelUserGroups withTrashed()
 * @method static Builder|ModelUserGroups withoutTrashed()
 * @mixin Eloquent
 * @property int|null $main_organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ModelUserGroups whereMainOrganizationId($value)
 */
class ModelUserGroups extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'group_id', 'main_organization_id'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function dictionaryGroup(): HasOne
    {
        return $this->hasOne(ModelDictionaryGroupUsers::class, 'id', 'group_id');
    }
}
