<?php

namespace App\Models\Templates;

use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\Models\Events\ModelEventsTemplates;
use App\Models\User\ModelUserGroups;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Templates\ModelTemplatesGroupUser
 *
 * @property int $priority
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ModelUserGroups[] $originalGroup
 * @property-read int|null $original_group_count
 * @property-read ModelTemplatesList $template
 * @method static Builder|ModelTemplatesGroupUser newModelQuery()
 * @method static Builder|ModelTemplatesGroupUser newQuery()
 * @method static Builder|ModelTemplatesGroupUser query()
 * @method static Builder|ModelTemplatesGroupUser whereCreatedAt($value)
 * @method static Builder|ModelTemplatesGroupUser whereGroupId($value)
 * @method static Builder|ModelTemplatesGroupUser whereId($value)
 * @method static Builder|ModelTemplatesGroupUser whereTemplateId($value)
 * @method static Builder|ModelTemplatesGroupUser whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $id
 * @property int $group_id
 * @property int $template_id
 * @method static Builder|ModelTemplatesGroupUser wherePriority($value)
 * @property int|null $main_organization_id
 * @property int|null $delay_send
 * @property-read \App\Models\Dictionary\ModelDictionaryGroupUsers $dictionaryGroup
 * @property-read \App\Models\Events\ModelEventsTemplates $templateEvent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Templates\ModelTemplatesGroupUser whereDelaySend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Templates\ModelTemplatesGroupUser whereMainOrganizationId($value)
 */
class ModelTemplatesGroupUser extends ModelTemplatesSchema
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'group_id',
        'main_organization_id',
        'delay_send',
        'priority'
    ];

    /**
     * @return HasOne
     */
    public function dictionaryGroup(): HasOne
    {
        return $this->hasOne(ModelDictionaryGroupUsers::class, 'id', 'group_id');
    }


    /**
     * @return HasMany
     */
    public function originalGroup(): HasMany
    {
        $this->connection = '';
        return $this->hasMany(ModelUserGroups::class, 'group_id', 'group_id')
            ->with(['user', 'dictionaryGroup']);
    }

    /**
     * @return HasOne
     */
    public function template(): HasOne
    {
        return $this->hasOne(ModelTemplatesList::class, 'id', 'template_id');
    }

    /**
     * @return HasOne
     */
    public function templateEvent(): HasOne
    {
        return $this->hasOne(ModelEventsTemplates::class, 'template_id', 'template_id')->with(['event']);
    }
}
