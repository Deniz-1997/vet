<?php

namespace App\Models\Dictionary;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Dictionary\ModelDictionaryOrganizations
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Dictionary\ModelDictionaryOrganizations withoutTrashed()
 * @mixin \Eloquent
 */
class ModelDictionaryOrganizations extends ModelDictionarySchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id'];
}
