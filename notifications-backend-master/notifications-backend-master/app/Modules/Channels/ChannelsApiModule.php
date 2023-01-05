<?php

namespace App\Modules\Channels;

use App\Modules\Module;
use App\Modules\TraitModule;
use Exception;
use Illuminate\Support\Str;

/**
 * Class ChannelsApiModule
 * @package App\Modules\Channels
 */
class ChannelsApiModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'channel_id' => ['required', 'numeric', 'exists:channels.model_channels,id'],
        'name' => ['required', 'string', 'max:255', 'unique:channels.model_channels_apis'],
    ];

    /**
     * @param array $array
     * @return mixed
     * @throws Exception
     */
    public static function create(array $array)
    {
        static::validation($array);

        $array['api_token'] = Str::random(60);

        $model = static::getModel()->create($array);

        static::setModel($model);

        return new static();
    }
}
