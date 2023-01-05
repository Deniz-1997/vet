<?php

namespace App\Models\Dictionary;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class ModelDictionaryGroupUsers
 * 
 * Словарь с типами историй
 *
 * @package App\Models\Dictionary
 * @property int $id
 * @property string $name
 * @property string $template
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType newQuery()
 * @method static Builder|ModelDictionaryHistoriesType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelDictionaryHistoriesType whereUpdatedAt($value)
 * @method static Builder|ModelDictionaryHistoriesType withTrashed()
 * @method static Builder|ModelDictionaryHistoriesType withoutTrashed()
 * @mixin Eloquent
 */
class ModelDictionaryHistoriesType extends ModelDictionarySchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'template'];
}
