<?php

namespace App\Http\Controllers\API\Internal\Channels;

use App\Http\Controllers\API\ApiController;
use App\Models\Channels\ModelChannelsApi;
use App\Modules\Channels\ChannelsApiModule;

/**
 * Class ApiControllerInternalChannelsApi
 *
 *
 * @package App\Http\Controllers\API\Internal\Channels
 */

/**
 * @OA\Tag(
 *      name="Internal Channels Apis",
 *      description="Внутренний компонент для работы с токенами для каналов"
 *  )
 *
 * @OA\Get(
 *     path="/v2/channels/apis",
 *     tags={"Internal Channels Apis"},
 *     summary="Возвращает токены канала для сервисов с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="ID канала"),
 *                  @OA\Property(property="api_token", type="integer", example=1, description="Токен"),
 *                  @OA\Property(property="name", type="integer", example=1, description="Имя для сервиса"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
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
 *     path="/v2/channels/apis",
 *     tags={"Internal Channels Apis"},
 *     summary="Создание токена для канала.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="channel_id", type="integer", example="true", description="ID канала"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя сервиса"),
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
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="ID канала"),
 *                  @OA\Property(property="api_token", type="integer", example=1, description="Токен"),
 *                  @OA\Property(property="name", type="integer", example=1, description="Имя для сервиса"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Put(
 *     path="/v2/channels/apis/{id}",
 *     tags={"Internal Channels Apis"},
 *     summary="Обновляем запись о токене канала.",
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
 *                  @OA\Property(property="channel_id", type="integer", example="true", description="ID канала"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя сервиса"),
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
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="ID канала"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Get(
 *     path="/v2/channels/apis/{id}",
 *     tags={"Internal Channels Apis"},
 *     summary="Возвращает оповещения конкретного токена канала.",
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
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="ID канала"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено"),
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
 *     path="/v2/channels/apis/{id}",
 *     tags={"Internal Channels Apis"},
 *     summary="Удаляем администратора канала.",
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
class ApiControllerInternalChannelsApi extends ApiController
{
    /**
     * ApiControllerInternalChannelsAdministrators constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(ChannelsApiModule::setModel(ModelChannelsApi::query()))
            ->setRoles(['administrator']);
    }
}
