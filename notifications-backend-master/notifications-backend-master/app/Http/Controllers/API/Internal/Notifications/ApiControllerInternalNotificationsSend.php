<?php

namespace App\Http\Controllers\API\Internal\Notifications;

use App\Exceptions\ValidationException;
use App\Http\Controllers\API\ApiController;
use App\Models\Notifications\ModelNotificationsSend;
use App\Modules\Notifications\NotificationsSendsModule;
use App\Traits\TraitValidator;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApiControllerInternalNotificationsSend
 *
 * @package App\Http\Controllers\API\Templates
 *
 * @OA\Tag(
 *      name="Internal Notifications Sends",
 *      description="Внутренний компонент для работы с оповещеними на отправку"
 *  )
 *
 * @OA\Get(
 *     path="/v2/notifications/sends",
 *     tags={"Internal Templates List"},
 *     summary="Возвращает оповещения на отправку с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="notify_event_id", type="integer", example=1, description="ID оповещения"),
 *                  @OA\Property(property="send_email", type="boolean", example=true, description="Отправка на почту"),
 *                  @OA\Property(property="send_sms", type="boolean", example=true, description="Отправка на телефон"),
 *                  @OA\Property(property="send_device", type="boolean", example=true, description="Отправка оповещение на девайс"),
 *                  @OA\Property(property="send", type="boolean", example=true, description="Отправлено"),
 *                  @OA\Property(property="viewed", type="boolean", example=true, description="Просмотрено"),
 *                  @OA\Property(property="sended_date", example="2020-01-01 19:00:00", type="string", description="Дата, когда будет отправлено оповещение"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *              )),
 *              @OA\Property(property="first_page_url", type="string", example=1, description="Адрес первой страницы с элементами"),
 *              @OA\Property(property="from", type="integer", example=1),
 *              @OA\Property(property="last_page", type="integer", example=1, description="Номер последней страницы"),
 *              @OA\Property(property="last_page_url", type="string", example=1, description="Последняя страница с элементами"),
 *              @OA\Property(property="next_page_url", type="string", example=1, description="Следующая страница с элементами"),
 *              @OA\Property(property="path", type="string", example=1, description="Адрес с элементами"),
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
 * @OA\Post(
 *     path="/v2/notifications/sends",
 *     tags={"Internal Templates List"},
 *     summary="Создание шаблона.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="notify_event_id", type="integer", example=1, description="ID оповещения"),
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=1, description="Статус"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="notify_event_id", type="integer", example=1, description="ID оповещения"),
 *                  @OA\Property(property="send_email", type="boolean", example=true, description="Отправка на почту"),
 *                  @OA\Property(property="send_sms", type="boolean", example=true, description="Отправка на телефон"),
 *                  @OA\Property(property="send_device", type="boolean", example=true, description="Отправка оповещение на девайс"),
 *                  @OA\Property(property="send", type="boolean", example=true, description="Отправлено"),
 *                  @OA\Property(property="viewed", type="boolean", example=true, description="Просмотрено"),
 *                  @OA\Property(property="sended_date", example="2020-01-01 19:00:00", type="string", description="Дата, когда будет отправлено оповещение"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата,когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Put(
 *     path="/v2/notifications/sends/{id}",
 *     tags={"Internal Templates List"},
 *     summary="Обновляем запись.",
 *     security={{"api_key": {}}},
 *     @OA\Parameter(
 *         description="ID записи",
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
 *                  @OA\Property(property="send", type="boolean", example=true, description="Отправлено"),
 *                  @OA\Property(property="viewed", type="boolean", example=true, description="Просмотрено"),
 *              )
 *          )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=1, description="Статус"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="notify_event_id", type="integer", example=1, description="ID оповещения"),
 *                  @OA\Property(property="send_email", type="boolean", example=true, description="Отправка на почту"),
 *                  @OA\Property(property="send_sms", type="boolean", example=true, description="Отправка на телефон"),
 *                  @OA\Property(property="send_device", type="boolean", example=true, description="Отправка оповещение на девайс"),
 *                  @OA\Property(property="send", type="boolean", example=true, description="Отправлено"),
 *                  @OA\Property(property="viewed", type="boolean", example=true, description="Просмотрено"),
 *                  @OA\Property(property="sended_date", example="2020-01-01 19:00:00", type="string", description="Дата, когда будет отправлено оповещение"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Get(
 *     path="/v2/notifications/sends/{id}",
 *     tags={"Internal Templates List"},
 *     summary="Возвращает запись.",
 *     security={{"api_key": {}}},
 *     @OA\Parameter(
 *         description="ID записи",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=1, description="Статус"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="notify_event_id", type="integer", example=1, description="ID оповещения"),
 *                  @OA\Property(property="send_email", type="boolean", example=true, description="Отправка на почту"),
 *                  @OA\Property(property="send_sms", type="boolean", example=true, description="Отправка на телефон"),
 *                  @OA\Property(property="send_device", type="boolean", example=true, description="Отправка оповещение на девайс"),
 *                  @OA\Property(property="send", type="boolean", example=true, description="Отправлено"),
 *                  @OA\Property(property="viewed", type="boolean", example=true, description="Просмотрено"),
 *                  @OA\Property(property="sended_date", example="2020-01-01 19:00:00", type="string", description="Дата, когда будет отправлено оповещение"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *              )),
 *          )
 *     ),
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Delete(
 *     path="/v2/notifications/sends/{id}",
 *     tags={"Internal Templates List"},
 *     summary="Удаляем запись об истории.",
 *     security={{"api_key": {}}},
 *     @OA\Parameter(
 *         description="ID записи",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="boolean", example=true, description="Статус"),
 *              @OA\Property(property="message", type="boolean", example="Record success deleted", description="Сообщение"),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 */
class ApiControllerInternalNotificationsSend extends ApiController
{
    use TraitValidator;

    /**
     * ApiControllerInternalNotificationsSend constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(NotificationsSendsModule::setModel(ModelNotificationsSend::query()))
            ->setRoles(['administrator']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array|Response|object
     * @throws Exception
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('administrator')) {
            return $this->sendError('Доступ запрещен.', Response::HTTP_FORBIDDEN);
        }

        $array = collect($request->post());

        try {
            static::$rules = [
                'user_id' => ['required', 'numeric', 'exists:users,id'],
                'notify_event_id' => ['required', 'numeric', 'exists:notifications.model_notifications_events,id'],
                'send_email' => ['boolean'],
                'send_sms' => ['boolean'],
                'send_device' => ['boolean'],
                'send' => ['boolean'],
                'viewed' => ['boolean'],
            ];

            static::validation($array->toArray());
        } catch (ValidationException $validationException) {
            # если данные не прошли валидацию
            return $this->sendError($this->validatorMessages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            return $this->sendError('Error create component (' . $this->getModel()->getTable() . '). Message: ' . $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $array->put('sended_date', Carbon::parse($array->get('sended_date'))->format('Y-m-d H:i:s'));

        $model = ModelNotificationsSend::create($array->toArray());

        $this->getModule()->setModel($model);

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
    }

}
