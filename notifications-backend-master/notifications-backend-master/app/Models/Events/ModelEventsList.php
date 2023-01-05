<?php

namespace App\Models\Events;


use App\Models\File\ModelFileList;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Events\ModelEventsList
 *
 * @property int $id
 * @property string $name
 * @property bool $hierarchy
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $file_id
 * @property-read Collection|ModelEventsTemplates[] $templates
 * @property-read int|null $templates_count
 * @method static Builder|ModelEventsList newModelQuery()
 * @method static Builder|ModelEventsList newQuery()
 * @method static Builder|ModelEventsList query()
 * @method static Builder|ModelEventsList whereCreatedAt($value)
 * @method static Builder|ModelEventsList whereDelaySendNotify($value)
 * @method static Builder|ModelEventsList whereDeletedAt($value)
 * @method static Builder|ModelEventsList whereHierarchy($value)
 * @method static Builder|ModelEventsList whereId($value)
 * @method static Builder|ModelEventsList whereName($value)
 * @method static Builder|ModelEventsList whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Events\ModelEventsList onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Events\ModelEventsList withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Events\ModelEventsList withoutTrashed()
 * @property int|null $main_organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsList whereMainOrganizationId($value)
 */
class ModelEventsList extends ModelEventsSchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'hierarchy',
        'main_organization_id',
    ];

    /**
     * @return HasOne
     */
    public function file(): HasOne
    {
        return $this->hasOne(ModelFileList::class, 'id', 'file_id');
    }

    /**
     * @return HasMany
     */
    public function templates(): HasMany
    {
        return $this->hasMany(ModelEventsTemplates::class, 'event_id', 'id')
            ->with(['originalTemplate']);
    }
}
