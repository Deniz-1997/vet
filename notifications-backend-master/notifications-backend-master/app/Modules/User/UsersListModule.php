<?php

namespace App\Modules\User;

use App\Models\User\ModelUserGroups;
use App\Modules\Module;
use App\Modules\TraitModule;
use Exception;
use Hash;
use Illuminate\Support\Str;

/**
 * Class HistoriesListModule
 * @package App\Modules\User
 */
class UsersListModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'max:255', 'unique:users'],
        'phone' => ['required']
    ];

    /**
     * @param array $array
     * @return mixed
     * @throws Exception
     */
    public static function create(array $array)
    {
        $collect = collect($array);

        static::validation($array);

        $model = static::getModel()->create([
            'name' => $collect->get('name'),
            'email' => $collect->get('email'),
            'password' => Hash::make(is_null($collect->get('password')) ? Str::random(8) : $collect->get('password')),
            'rate_limit' => 60,
            'phone' => $collect->get('phone'),
            'organization_id' => $collect->get('organization_id'),
            'api_token' => Str::random(60)
        ]);

        static::setModel($model);

        if (!is_null($collect->get('groups'))) {
            foreach ($collect->get('groups') as $id) {
                ModelUserGroups::create([
                    'user_id' => $model->id,
                    'group_id' => $id
                ]);
            }
        }

        return new static();
    }
}
