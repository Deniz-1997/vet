<?php

namespace App\Modules\Notifications;

use App\Exceptions\ValidationException;
use App\Jobs\JobSendNotifications;
use App\Logger;
use App\Models\Channels\ModelChannelsEvent;
use App\Models\Channels\ModelChannelsUsers;
use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\Models\Dictionary\ModelDictionaryOrganizations;
use App\Models\Events\ModelEventsList;
use App\Models\Notifications\ModelNotificationsEvents;
use App\Models\Notifications\ModelNotificationsList;
use App\Models\Notifications\ModelNotificationsSend;
use App\Models\Templates\ModelTemplatesGroupUser;
use App\Models\User\ModelUserGroups;
use App\Modules\Dictionary\DictionaryGroupUsersModule;
use App\Modules\Dictionary\DictionaryOrganizationsModule;
use App\Modules\Module;
use App\Modules\TraitModule;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class NotificationsSendsModule
 * @package App\Modules\Notifications
 */
class NotificationsSendsModule extends Module
{
    use TraitModule;

    /**
     * Правила для валидации
     *
     * @var array
     */
    public static $rules = [
        'user_id' => ['required', 'numeric', 'exists:users,id'],
        'notify_event_id' => ['required', 'numeric', 'exists:notifications.model_notifications_events,id'],
        'send_email' => ['boolean'],
        'send_sms' => ['boolean'],
        'send_device' => ['boolean'],
        'send' => ['boolean'],
        'viewed' => ['boolean'],
    ];

    /**
     * Модель с оповещениями
     *
     * @var ModelNotificationsList
     */
    private $_modelNotifications;

    /**
     * Модель с событием
     *
     * @var ModelEventsList
     */
    private $_modelEvent;

    /**
     * Пользователь канала
     *
     * @var ModelChannelsUsers[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection
     */
    private $_userOfChannels;

    /**
     * Пользователи которым отправится оповещение
     *
     * @var Collection
     */
    private $_userForNotifications;

    /**
     * Шаблоны для оповещений
     *
     * @var Collection
     */
    private $_templates;

    /**
     * @var Collection
     */
    private $_organizations;

    /**
     * @var int
     */
    private $_org_id;


    public static function allowedSend(bool $allowed, int $notify_event_id)
    {
        $models = ModelNotificationsSend::whereNotifyEventId($notify_event_id)->whereError(false)->whereSend(false)->get();

        foreach ($models as $model) {
            $model->allowed_to_send = !$allowed;
            $model->save();

            if ($allowed) {
                try {
                    Logger::error('Отправка разрешенного уведомления ' . $model->allowed_to_send);
                    JobSendNotifications::dispatch($model)
                        ->delay(1 * 60 + rand(5, 30))
                        ->onQueue(env('TUBE_NOTIFICATIONS'));
                } catch (Exception $exception) {
                    Logger::error($exception->getMessage() . "  Ошибка отправки оповещение $model->id");
                }
            }
        }
    }

    /**
     * Проверяем данные для отправки оповещении
     *
     *
     * @param Collection $collection
     * @return $this
     * @throws ValidationException
     */
    public function validationSendNotify(Collection $collection): NotificationsSendsModule
    {
        $this->_modelNotifications = ModelNotificationsList::whereChannelId($collection->get('channel_id'))
            ->whereId($collection->get('notify_id'))
            ->first();

        if (is_null($this->_modelNotifications)) {
            throw new ValidationException("Нет доступа к каналу {$collection->get('notify_id')}");
        }

        $event_id = $this->_modelNotifications->event_id;

        $this->_modelEvent = ModelEventsList::with(['templates'])
            ->whereId($event_id)
            ->first();

        if (is_null($this->_modelEvent)) {
            throw new ValidationException("Не найдено событие: {$event_id}");
        }

        if (!ModelChannelsEvent::whereChannelId($collection->get('channel_id'))
            ->whereEventId($event_id)
            ->exists()) {
            throw new ValidationException("Событие не добавлено в текущий канал. ID события: {$event_id}");
        }

//        $eventsTemplate = $this->_modelEvent->templates;

        if ($this->_modelEvent->templates->count() === 0) {
            throw new ValidationException("Для текущего события ({$event_id}) не добавлен шаблоны");
        }

        return $this;
    }

    /**
     * Добавляем в переменную пользователей канала
     *
     * @param int $channel_id
     * @return $this
     */
    public function setUsersOfChannel(int $channel_id)
    {
        $this->_userOfChannels = ModelChannelsUsers::whereChannelId($channel_id)->select('user_id')->get()->map(function ($e) {
            return $e->user_id;
        });

        return $this;
    }

    /**
     * Добавляем в переменную шаблоны, по событию
     *
     * @return $this
     * @throws ValidationException
     */
    public function setTemplate()
    {
        $this->_templates = collect();

        foreach ($this->_modelEvent->templates as $k => $eventTemplate) {

            # определяем оригинал шаблон
            $template = $eventTemplate->originalTemplate;

            if (is_null($template)) {
                throw new ValidationException("Не найден оригинальный шаблон для шаблона №{$eventTemplate->template_id}");
            }

            # определем группы пользователей
            $templateUserGroup = $template->templateUserGroup;

            if ($templateUserGroup->count() === 0) {
                throw new ValidationException("Не найдены группы пользователей для шаблона №{$template->template_id}");
            }

            # составляем массив на шаблон с группами пользователей
            $groupUser = $this->_getGroupUserOnTemplate($templateUserGroup);

            $this->_templates->put($eventTemplate->template_id, $groupUser);
        }

        return $this;
    }

    /**
     * Добавляем в переменную организации которые по иерархии выше текущей
     *
     * @param int $org_id
     * @return $this
     */
    public function setParentOrganizations(int $org_id)
    {
        $this->_org_id = $org_id;

        $this->_organizations = $this->getParentOrganizations($org_id);

        return $this;
    }

    /**
     * Добавляем в переменую пользователей кому отправится уведомление
     *
     * @return $this
     */
    public function convertUsersForNotifications()
    {
        $this->_userForNotifications = collect();

        foreach ($this->_userOfChannels as $user_id) {
            $templateId = $this->_getTemplateGroup($user_id, $this->_templates);

            if (is_null($templateId)) {
                continue;
            }

            $groupId = $this->_getGroupId($user_id, $this->_templates->get($templateId));

            if ($groupId === 0) {
                continue;
            }

            $this->_userForNotifications->put($user_id, collect([
                'template_id' => $templateId,
                'group_id' => $groupId
            ]));
        }

        return $this;
    }

    /**
     * Возвращаем пользователей, кому отправится уведомление
     *
     * @return Collection
     */
    public function getUsersForNotifications(): Collection
    {
        return $this->_userForNotifications;
    }

    /**
     * Отправляем оповещение
     *
     * @param array $array_for_events
     * @return Collection
     */
    public function sendNotifications(array $array_for_events): Collection
    {

        $array_for_events['notifications_id'] = $this->_modelNotifications->id;
        $array_for_events['event_id'] = $this->_modelEvent->id;

        $notifications_array = collect();
        $users_allowed_to_send = collect();
        $users_temp = collect();

        $users = $this->getUsersForNotifications();

        if ($users->count() === 0) {
            Logger::critical('Not found users for send notify. Data: '.json_encode($array_for_events));
            return $users_temp;
        }

        $modelNotificationsEvent = ModelNotificationsEvents::firstOrCreate($array_for_events);

        foreach ($users as $user_id => $array) {
            $user = User::whereId($user_id)->select('organization_id')->first();

            if (!is_null($user)) {
                $organization_id = User::whereId($user_id)->select('organization_id')->first()->organization_id;

                $template = ModelTemplatesGroupUser::whereTemplateId($array['template_id'])
                    ->with('templateEvent')
                    ->select(['delay_send', 'template_id'])->first();

                # если в событии включен флаг иерархии, то отправляем только тем организациям которые выше текущей
                if ($this->_modelEvent->hierarchy) {
                    if ($this->_organizations->search($organization_id) === false) {
                        continue;
                    }
                }

                $notifications_array->push(collect([
                    'user_id' => $user_id,
                    'notify_event_id' => $modelNotificationsEvent->id,
                    'send' => false,
                    'sended_date' => Carbon::now()->startOfMinute()->addMinutes($template->delay_send),
                    'need_permission_to_send' => false,
                    'allowed_to_send' => DictionaryGroupUsersModule::checkFilterNotification($user_id),
                    'delay_send' => $template->delay_send
                ]));
            }
        }

        if($notifications_array->count() === 0){
            Logger::critical('Not found users for send notify[2]. Data: '.json_encode($array_for_events));
            $modelNotificationsEvent->delete();
            return $users_temp;
        }

        $isset_allowed_to_send = $this->_issetAllowedToSendInCollection($notifications_array);

        foreach ($notifications_array as $item) {

            if ($isset_allowed_to_send) $item->put('need_permission_to_send', DictionaryGroupUsersModule::checkResponsibleNotification($item->get('user_id')));

            $notifications = ModelNotificationsSend::create($item->toArray());

            if (!$notifications->allowed_to_send) {
                try {
                    JobSendNotifications::dispatch($notifications)
                        ->delay($item->get('delay_send') * 60 + rand(5, 120))
                        ->onQueue(env('TUBE_NOTIFICATIONS'));
                } catch (Exception $exception) {
                    Logger::error($exception->getMessage() . "  Ошибка отправки оповещение $notifications->id");
                }
            }

            $users_temp->push($notifications->id);
        }

        return $users_temp;
    }

    /**
     * @param int $organization_id
     * @return Collection
     */
    private function getParentOrganizations(int $organization_id): Collection
    {
        $organizations = collect();

        $parent_not_null = true;
        $id = $organization_id;

        $organizations->push($id);

        do {
            $model = ModelDictionaryOrganizations::find($id);

            if (is_null($model->parent_id)) {
                $parent_not_null = false;
                $id = $model->id;
            } else {
                $id = $model->parent_id;
            }

            $organizations->push($id);
        } while ($parent_not_null);

        return $organizations->unique()->values();
    }

    /**
     * Возвращаем массив с шаблонами и пользователем
     *
     * @param Collection $groupUsers
     * @return Collection
     */
    private function _getGroupUserOnTemplate(Collection $groupUsers): Collection
    {
        $templateCollection = collect();

        foreach ($groupUsers as $groupUser) {
            $originalGroup = $groupUser->originalGroup;
            $templateCollection->put($groupUser->group_id, collect([]));

            foreach ($originalGroup as $item) {
                if ($this->_userOfChannels->search($item->user_id) !== false) {
                    $templateCollection->get($groupUser->group_id)->push($item->user_id);
                }
            }
        }

        return $templateCollection;
    }

    /**
     * Если пользователь находится в нескольких шаблонах, то возвращаем тот, который имеет наименьшее время отправки
     *
     * @param int $user_id
     * @param Collection $templates
     * @return Collection|mixed
     */
    private function _getTemplateGroup(int $user_id, Collection $templates)
    {
        $delay = collect();

        foreach ($templates as $group_id => $template) {
            foreach ($template as $item) {
                if ($item instanceof Collection) {
                    if ($item->search($user_id) !== false) {
                        $model = ModelTemplatesGroupUser::whereTemplateId($group_id)->select('delay_send')->first();
                        $delay->put($model->delay_send, $group_id);
                    }
                }
            }
        }

        return $delay->count() > 0 ? $delay->sortKeys()->first() : null;
    }

    /**
     * Возвращаем к какой группе относится пользователь соортируя по приоритету
     *
     * @param int $user_id
     * @param Collection $groups
     * @return int
     */
    private function _getGroupId(int $user_id, Collection $groups): int
    {
        $temp = collect();

        foreach ($groups as $group_id => $group) {
            if ($group->search($user_id) !== false) {
                $model = ModelTemplatesGroupUser::whereGroupId($group_id)->select('priority')->first();
                $temp->put($model->priority, $group_id);
            }
        }

        return $temp->count() > 0 ? $temp->sortKeys()->last() : 0;
    }

    /**
     * Проверяем что существует пользователь который имеет группу для фильтрации отправки оповещений
     *
     * @param Collection $notifications_array
     * @return bool
     */
    private function _issetAllowedToSendInCollection(Collection $notifications_array)
    {
        foreach ($notifications_array->toArray() as $item) {
            if (isset($item['allowed_to_send']) && $item['allowed_to_send']) {
                return true;
            }
        }

        return false;
    }
}
