<?php

namespace App\Models\Events;

use App\Models\Templates\ModelTemplatesList;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Events\ModelEventsTemplates
 *
 * @property int $id
 * @property int $template_id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Events\ModelEventsList $event
 * @property-read \App\Models\Templates\ModelTemplatesList $originalTemplate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Events\ModelEventsTemplates onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Events\ModelEventsTemplates withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Events\ModelEventsTemplates withoutTrashed()
 * @property int|null $main_organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Events\ModelEventsTemplates whereMainOrganizationId($value)
 */
class ModelEventsTemplates extends ModelEventsSchema
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'event_id',
        'main_organization_id',
    ];

    /**
     * @return HasOne
     */
    public function originalTemplate(): HasOne
    {
        return $this->hasOne(ModelTemplatesList::class, 'id', 'template_id')
            ->with(['templateUserGroup']);
    }

    /**
     * @return HasOne
     */
    public function event(): HasOne
    {
        return $this->hasOne(ModelEventsList::class, 'id', 'event_id');
    }
}
