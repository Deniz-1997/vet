<?php

namespace App\Modules\Events;

use App\Modules\Module;
use App\Modules\TraitModule;
use Exception;


/**
 * Class EventsTemplatesModule
 * @package App\Modules\Events
 */
class EventsTemplatesModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'template_id' => ['required', 'numeric', 'exists:templates.model_templates_lists,id'],
        'event_id' => ['required', 'numeric', 'exists:events.model_events_lists,id'],
    ];


    /**
     * @param array $array
     * @return mixed
     * @throws Exception
     */
    public static function create(array $array)
    {
        static::validation($array);

        if (static::getModel()->whereTemplateId($array['template_id'])->whereEventId($array['event_id'])->exists()) {
            throw new Exception('Запись по текущему шаблону и событию уже существует.');
        }

        $model = static::getModel()->create($array);

        static::setModel($model);

        return new static();
    }

}
