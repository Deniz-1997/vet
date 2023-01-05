<?php

namespace App\Models\Templates;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Templates\ModelTemplatesList
 *
 * @property int $id
 * @property string $name
 * @property string|null $text
 * @property string $color
 * @property string $format_date
 * @property bool $show_status_notify
 * @property bool $show_date
 * @property bool $show_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|ModelTemplatesGroupUser[] $templateUserGroup
 * @property-read int|null $template_user_group_count
 * @method static Builder|ModelTemplatesList newModelQuery()
 * @method static Builder|ModelTemplatesList newQuery()
 * @method static Builder|ModelTemplatesList query()
 * @method static Builder|ModelTemplatesList whereColor($value)
 * @method static Builder|ModelTemplatesList whereCreatedAt($value)
 * @method static Builder|ModelTemplatesList whereDeletedAt($value)
 * @method static Builder|ModelTemplatesList whereFormatDate($value)
 * @method static Builder|ModelTemplatesList whereId($value)
 * @method static Builder|ModelTemplatesList whereShowDate($value)
 * @method static Builder|ModelTemplatesList whereShowStatusNotify($value)
 * @method static Builder|ModelTemplatesList whereShowTime($value)
 * @method static Builder|ModelTemplatesList whereText($value)
 * @method static Builder|ModelTemplatesList whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $main_organization_id
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Templates\ModelTemplatesList onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Templates\ModelTemplatesList whereMainOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Templates\ModelTemplatesList whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Templates\ModelTemplatesList withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Templates\ModelTemplatesList withoutTrashed()
 */
class ModelTemplatesList extends ModelTemplatesSchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'text',
        'color',
        'format_date',
        'show_status_notify',
        'show_date',
        'main_organization_id',
        'show_time',
    ];

    /**
     * @return HasMany
     */
    public function templateUserGroup(): HasMany
    {
        return $this->hasMany(ModelTemplatesGroupUser::class, 'template_id', 'id')
            ->with(['originalGroup']);
    }
}
