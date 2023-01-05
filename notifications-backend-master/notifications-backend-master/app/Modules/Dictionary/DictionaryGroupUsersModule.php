<?php

namespace App\Modules\Dictionary;

use App\Models\User\ModelUserGroups;
use App\Modules\Module;
use App\Modules\TraitModule;

/**
 * Class DictionaryGroupUsersModule
 * @package App\Modules\Dictionary
 */
class DictionaryGroupUsersModule extends Module
{
    use TraitModule;

    /**
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:dictionary.model_dictionary_group_users'],
        'delay_send' => ['numeric', 'max:32767']
    ];

    public static function checkResponsibleNotification(int $user_id): bool
    {
        $cache = \Cache::get("check_responsible_notification_$user_id");

        if(is_null($cache)){

            $groups = ModelUserGroups::whereUserId($user_id)->with(['dictionaryGroup'])->get();

            foreach ($groups as $group) {
                if(is_null($group->dictionaryGroup)){
                    $group->delete();
                    continue;
                }

                if($group->dictionaryGroup->responsible_notifications){
                    \Cache::add("check_responsible_notification_$user_id", true, 60);
                    return true;
                }
            }

            \Cache::add("check_responsible_notification_$user_id", false, 60);
            return false;
        }

        return $cache;
    }

    /**
     * Проверяем группы пользователя, если группа относится к фильтрующей, то значит требуется разрешение на отправку
     *
     * @param int $user_id
     * @return bool
     */
    public static function checkFilterNotification(int $user_id): bool
    {
        $cache = \Cache::get("check_filter_notification_$user_id");

        if(is_null($cache)){

            $groups = ModelUserGroups::whereUserId($user_id)->with(['dictionaryGroup'])->get();

            foreach ($groups as $group) {
                if(is_null($group->dictionaryGroup)){
                    continue;
                }

                if($group->dictionaryGroup->filter_notifications){
                    \Cache::add("check_filter_notification_$user_id", true, 60);
                    return true;
                }
            }

            \Cache::add("check_filter_notification_$user_id", false, 60);
            return false;
        }

        return $cache;
    }
}
