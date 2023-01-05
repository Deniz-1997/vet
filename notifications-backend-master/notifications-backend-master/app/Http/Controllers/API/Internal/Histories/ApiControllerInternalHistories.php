<?php

namespace App\Http\Controllers\API\Internal\Histories;

use App\Http\Controllers\API\ApiController;
use App\Models\Histories\ModelHistories;
use App\Modules\Histories\HistoriesListModule;

/**
 * Class ApiControllerInternalHistories
 *
 *
 * @package App\Http\Controllers\API\Channels
 */

/**
 * @OA\Tag(
 *      name="Internal Histories List",
 *      description="Внутренний компонент для работы с историями"
 *  )
 *
 * @OA\Get(
 *     path="/v2/histories/list",
 *     tags={"Internal Histories List"},
 *     summary="Возвращает истории с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="history_id", type="integer", example=1, description="ID истории"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="data", type="string", example="{type:'user'}", description="JSON массив с данными для истории"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено оповещение"),
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
 *     path="/v2/histories/list",
 *     tags={"Internal Histories List"},
 *     summary="Создание истории.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="history_id", type="integer", example=1, description="ID истории"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользовател"),
 *                  @OA\Property(property="data", type="string", example="{type:'user'}", description="JSON массив с данными для истории"),
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
 *                  @OA\Property(property="history_id", type="integer", example=1, description="ID истории"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="data", type="string", example="{type:'user'}", description="JSON массив с данными для истории"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено оповещение"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Put(
 *     path="/v2/histories/list/{id}",
 *     tags={"Internal Histories List"},
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
 *                  @OA\Property(property="history_id", type="integer", example=1, description="ID истории"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="data", type="string", example="{type:'user'}", description="JSON массив с данными для истории"),
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
 *                  @OA\Property(property="history_id", type="integer", example=1, description="ID истории"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользовател"),
 *                  @OA\Property(property="data", type="string", example="{type:'user'}", description="JSON массив с данными для истории"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено оповещение"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Get(
 *     path="/v2/histories/list/{id}",
 *     tags={"Internal Histories List"},
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
 *                  @OA\Property(property="history_id", type="integer", example=1, description="ID истории"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользовател"),
 *                  @OA\Property(property="data", type="string", example="{type:'user'}", description="JSON массив с данными для истории"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено оповещение"),
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
 *     path="/v2/histories/list/{id}",
 *     tags={"Internal Histories List"},
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
class ApiControllerInternalHistories extends ApiController
{
    /**
     * ApiControllerInternalHistories constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(HistoriesListModule::setModel(ModelHistories::query()))
            ->setRoles(['administrator']);
    }
}
