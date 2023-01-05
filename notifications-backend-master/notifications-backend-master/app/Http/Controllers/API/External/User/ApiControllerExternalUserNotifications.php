<?php

namespace App\Http\Controllers\API\External\User;

use App\Exceptions\ValidationException;
use App\Http\Controllers\API\ApiController;
use App\Models\Channels\ModelChannelsNotificationsCount;
use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\Models\Notifications\ModelNotificationsEvents;
use App\Models\Notifications\ModelNotificationsPermissions;
use App\Models\Notifications\ModelNotificationsSend;
use App\Models\User\ModelUserGroups;
use App\Modules\Dictionary\DictionaryGroupUsersModule;
use App\Modules\Notifications\NotificationsSendsModule;
use App\Traits\TraitValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApiControllerExternalUserChannel
 *
 * @OA\Tag(
 *      name="External User",
 *      description="Внешний компонент для работы с пользователями"
 *  )
 *
 * @package App\Http\Controllers\API\External\User
 */
class ApiControllerExternalUserNotifications extends ApiController
{
    use TraitValidator;

    /**
     * @OA\Get(
     *     path="/v1/user/notifications",
     *     tags={"External User"},
     *     summary="Возвращает оповещения доступные пользователю.",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         description="Количество элементов на одной странице (Необязательно)",
     *         required=false,in="query",name="count",
     *         @OA\Schema(type="integer"),style="form"
     *     ),
     *     @OA\Parameter(
     *         description="Номер страницы с элементами (Необязательно)",
     *         required=false,in="query",name="page",
     *         @OA\Schema(type="integer"),style="form"
     *     ),
     *     @OA\Parameter(
     *         description="ID канала.",
     *         required=true,in="query",name="channel_id",
     *         @OA\Schema(type="integer"),style="form"
     *     ),
     *     @OA\Parameter(
     *         description="ID события.",
     *         required=true,in="query",name="event_id",
     *         @OA\Schema(type="integer"),style="form"
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
     *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1, description="ID оповещения"),
     *                  @OA\Property(property="message", type="string", description="Текст оповещения"),
     *                  @OA\Property(property="datetime", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
     *                  @OA\Property(property="viewed", type="boolean", description="Просмотрено-ли"),
     *              )),
     *              @OA\Property(property="first_page_url", type="string", example=1, description="Адрес первой страницы с оповещениями"),
     *              @OA\Property(property="from", type="integer", example=1),
     *              @OA\Property(property="last_page", type="integer", example=1, description="Номер последней страницы"),
     *              @OA\Property(property="last_page_url", type="string", example=1, description="Последняя страница с оповещениями"),
     *              @OA\Property(property="next_page_url", type="string", example=1, description="Следующая страница с оповещениями"),
     *              @OA\Property(property="path", type="string", example=1, description="Адрес с оповещениями"),
     *              @OA\Property(property="per_page", type="integer", example=1, description="Предыдущая страница"),
     *              @OA\Property(property="prev_page_url", type="string", example=1, description="Предыдущая страница с элементами"),
     *              @OA\Property(property="to", type="integer", example=1),
     *              @OA\Property(property="total", type="integer", example=10, description="Всего страниц с элементами"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error server",
     *     )
     * )
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $this->setPaginator(true);

        $channel_id = $request->get('channel_id');

        if (is_null($channel_id)) {
            return $this->sendError('Пожалуйста, укажите channel_id', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $event_id = $request->get('event_id');

        if (is_null($event_id)) {
            return $this->sendError('Пожалуйста, укажите event_id', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = Auth()->user()->originalUser();

        $model = ModelNotificationsSend::select([
            'model_notifications_sends.id',
            'model_notifications_sends.created_at as datetime',
            'model_notifications_sends.viewed',
            'notifications.model_notifications_events.event_id as icon_event_id',
            'notifications.model_notifications_events.text as message',
            'notifications.model_notifications_events.good_emoji as state_notifications',
            'model_notifications_sends.need_permission_to_send as need_permission',
            'notifications.model_notifications_permissions.allow_send as has_permissions'
        ])
            ->whereUserId($user->id)
            ->leftJoin('notifications.model_notifications_permissions', 'notifications.model_notifications_sends.id', '=', 'notifications.model_notifications_permissions.notification_id')
            ->leftJoin('notifications.model_notifications_events', 'notify_event_id', '=', 'notifications.model_notifications_events.id')
            ->leftJoin('notifications.model_notifications_lists', 'notifications.model_notifications_events.notifications_id', '=', 'notifications.model_notifications_lists.id')
            ->leftJoin('events.model_events_templates', 'notifications.model_notifications_events.event_id', '=', 'events.model_events_templates.id')
            ->where('notifications.model_notifications_sends.send', '=', true)
            ->orderBy('model_notifications_sends.created_at', 'DESC');

//        if($channel_id == 18 && $event_id == 789){
//            $model->limit(40);
//        } else {
            $model
                ->where('notifications.model_notifications_lists.channel_id', '=', $channel_id)
                ->where('notifications.model_notifications_events.notifications_id', '=', $event_id);
//        }

        $this->setCallbackPagination(function ($value) use ($user) {
            if($value->need_permission){
                # если оповещение требует подтверждение отправки и пользователь не имеет роль ответственного, не показываем кнопки
                if(!DictionaryGroupUsersModule::checkResponsibleNotification($user->id)){
                    $value->need_permission = false;
                }
            }

            return $value;
        });

        return $this->sendResponse($model, $request);
    }

    /**
     * @OA\Post(
     *     path="/v1/user/notifications/{id}",
     *     tags={"External User"},
     *     summary="Обновление информации об оповещении пользователя.",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         description="ID оповещения",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="viewed", type="boolean", example="true", description="Обновление информации о том, что пользователь увидел оповещение")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
     *                  @OA\Property(property="msg", example="Запись успешно обновлена", type="string", description="Ответ от сервера"),
     *              )),
     *          )
     *     ),
     *     @OA\Response(response=400,description="Error validation"),
     *     @OA\Response(response=500,description="Error server")
     * )
     *
     * @param Request $request
     * @param int $id
     * @return array|Response|object|void
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $user_id = Auth()->user()->originalUser()->id;

        $model = ModelNotificationsSend::whereUserId($user_id)
            ->whereSend(true)
            ->whereId($id)
            ->with(['notifyEventWithoutWith'])
            ->first();

        if (is_null($model)) {
            return $this->sendError('Оповещение не найдено', Response::HTTP_NOT_FOUND);
        }

        try {
            if (!is_null($request->get('viewed'))) {
                static::$rules = ['viewed' => ['required', 'boolean']];
                static::validation($request->post());
                if ($request->get('viewed') !== $model->viewed) {
                    $model->viewed = $request->get('viewed');
                }
            }

            if (!is_null($request->get('allowed_to_send'))) {
                static::$rules = ['allowed_to_send' => ['required', 'boolean']];
                static::validation($request->post());
                $allowed_to_send = $request->get('allowed_to_send');

                $model->allowed_to_send = $allowed_to_send;

                ModelNotificationsPermissions::firstOrCreate([
                    'notification_id' => $model->id,
                    'allow_send' => $allowed_to_send,
                    'created_user_id' => $user_id
                ]);

                NotificationsSendsModule::allowedSend($allowed_to_send, $model->notify_event_id);
            }
        } catch (ValidationException $exception) {
            return $this->sendError(self::validatorMessages(), Response::HTTP_BAD_REQUEST);
        }

        $model->save();

        $notifications_id = $model->notifyEventWithoutWith->notifications_id;

        $events = ModelNotificationsEvents::whereNotificationsId($notifications_id)
            ->leftJoin('notifications.model_notifications_sends',
                'notify_event_id', '=', 'notifications.model_notifications_events.id')
            ->where('notifications.model_notifications_sends.send', '=', true)
            ->where('notifications.model_notifications_sends.viewed', '=', false)
            ->where('notifications.model_notifications_sends.user_id', '=', $user_id)
            ->get();

        $count = $events->count();


        $modelNoti = ModelChannelsNotificationsCount::firstOrCreate([
            'notification_id' => $notifications_id,
            'user_id' => $user_id,
        ]);

        $modelNoti->count = $count;

        $modelNoti->save();

        return $this->sendResponse(['msg' => 'Запись успешно обновлена'], $request);
    }

}
