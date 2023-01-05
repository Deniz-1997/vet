<?php

namespace App\Http\Controllers\API\Internal\Notifications;

use App\Http\Controllers\API\ApiController;
use App\Models\Notifications\ModelNotificationsList;
use App\Modules\Notifications\NotificationsListModule;

/**
 * Class ApiControllerInternalNotificationsList
 *
 * @package App\Http\Controllers\API\Notifications
 *
 * @OA\Tag(
 *      name="Internal Notifications List",
 *      description="Внутренний компонент для работы с оповещениями"
 *  )
 *
 * @OA\Get(
 *     path="/v2/notifications/list",
 *     tags={"Internal Notifications List"},
 *     summary="Возвращает оповещения с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="К какому каналу относится оповещение"),
 *                  @OA\Property(property="name", type="string", example="Тестовое оповещение", description="Название оповещения"),
 *                  @OA\Property(property="status", type="string", example="Открыт", description="Статус оповещения"),
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
 *     path="/v2/notifications/list",
 *     tags={"Internal Notifications List"},
 *     summary="Создание оповещение.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="name", type="string", example="АЧС в Рузском районе 18.01.2020", description="Имя оповещения"),
 *                  @OA\Property(property="status", type="string", example="Открыт", description="Статус оповещения"),
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
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="К какому каналу относится оповещение"),
 *                  @OA\Property(property="name", type="string", example="Тестовое оповещение", description="Название оповещения"),
 *                  @OA\Property(property="status", type="string", example="Открыт", description="Статус оповещения"),
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
 * @OA\Put(
 *     path="/v2/notifications/list/{id}",
 *     tags={"Internal Notifications List"},
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
 *                  @OA\Property(property="status", type="string", example="Закрыто", description="Статус оповещения"),
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
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="К какому каналу относится оповещение"),
 *                  @OA\Property(property="name", type="string", example="Тестовое оповещение", description="Название оповещения"),
 *                  @OA\Property(property="status", type="string", example="Открыт", description="Статус оповещения"),
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
 *     path="/v2/notifications/list/{id}",
 *     tags={"Internal Notifications List"},
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
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="К какому каналу относится оповещение"),
 *                  @OA\Property(property="name", type="string", example="Тестовое оповещение", description="Название оповещения"),
 *                  @OA\Property(property="status", type="string", example="Открыт", description="Статус оповещения"),
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
 *     path="/v2/notifications/list/{id}",
 *     tags={"Internal Notifications List"},
 *     summary="Удаляем запись.",
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
class ApiControllerInternalNotificationsList extends ApiController
{
    /**
     * ApiControllerInternalNotificationsList constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(NotificationsListModule::setModel(ModelNotificationsList::query()))
            ->setRoles(['administrator']);
    }
}
