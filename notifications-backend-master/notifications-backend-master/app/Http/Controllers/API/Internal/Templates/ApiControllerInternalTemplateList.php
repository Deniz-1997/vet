<?php

namespace App\Http\Controllers\API\Internal\Templates;

use App\Http\Controllers\API\ApiController;
use App\Models\Templates\ModelTemplatesList;
use App\Modules\Templates\TemplatesListModule;

/**
 * Class ApiControllerInternalTemplateList
 *
 *
 * @package App\Http\Controllers\API\Templates
 */

/**
 * @OA\Tag(
 *      name="Internal Templates List",
 *      description="Внутренний компонент для работы с шаблонами"
 *  )
 *
 * @OA\Get(
 *     path="/v2/template/list",
 *     tags={"Internal Templates List"},
 *     summary="Возвращает шаблоны с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="color", type="string", example="#fff930", description="Цвет текста"),
 *                  @OA\Property(property="format_date", type="string", example="YYYY-MM-DD H:i:s", description="Формат даты"),
 *                  @OA\Property(property="show_status_notify", type="boolean", example=true, description="Показывать ли статус оповещения"),
 *                  @OA\Property(property="show_date", type="string", example=false, description="Показывать ли дату"),
 *                  @OA\Property(property="show_time", type="boolean", example=true, description="Показывать ли время"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено"),
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
 *     path="/v2/template/list",
 *     tags={"Internal Templates List"},
 *     summary="Создание шаблона.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="color", type="string", example="#fff930", description="Цвет текста"),
 *                  @OA\Property(property="format_date", type="string", example="YYYY-MM-DD H:i:s", description="Формат даты"),
 *                  @OA\Property(property="show_status_notify", type="boolean", example=true, description="Показывать ли статус оповещения"),
 *                  @OA\Property(property="show_date", type="string", example=false, description="Показывать ли дату"),
 *                  @OA\Property(property="show_time", type="boolean", example=true, description="Показывать ли время"),
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
 *                  @OA\Property(property="color", type="string", example="#fff930", description="Цвет текста"),
 *                  @OA\Property(property="format_date", type="string", example="YYYY-MM-DD H:i:s", description="Формат даты"),
 *                  @OA\Property(property="show_status_notify", type="boolean", example=true, description="Показывать ли статус оповещения"),
 *                  @OA\Property(property="show_date", type="string", example=false, description="Показывать ли дату"),
 *                  @OA\Property(property="show_time", type="boolean", example=true, description="Показывать ли время"),
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
 * @OA\Put(
 *     path="/v2/template/list/{id}",
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
 *                  @OA\Property(property="color", type="string", example="#fff930", description="Цвет текста"),
 *                  @OA\Property(property="format_date", type="string", example="YYYY-MM-DD H:i:s", description="Формат даты"),
 *                  @OA\Property(property="show_status_notify", type="boolean", example=true, description="Показывать ли статус оповещения"),
 *                  @OA\Property(property="show_date", type="string", example=false, description="Показывать ли дату"),
 *                  @OA\Property(property="show_time", type="boolean", example=true, description="Показывать ли время"),
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
 *                  @OA\Property(property="color", type="string", example="#fff930", description="Цвет текста"),
 *                  @OA\Property(property="format_date", type="string", example="YYYY-MM-DD H:i:s", description="Формат даты"),
 *                  @OA\Property(property="show_status_notify", type="boolean", example=true, description="Показывать ли статус оповещения"),
 *                  @OA\Property(property="show_date", type="string", example=false, description="Показывать ли дату"),
 *                  @OA\Property(property="show_time", type="boolean", example=true, description="Показывать ли время"),
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
 *     path="/v2/template/list/{id}",
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
 *                  @OA\Property(property="color", type="string", example="#fff930", description="Цвет текста"),
 *                  @OA\Property(property="format_date", type="string", example="YYYY-MM-DD H:i:s", description="Формат даты"),
 *                  @OA\Property(property="show_status_notify", type="boolean", example=true, description="Показывать ли статус оповещения"),
 *                  @OA\Property(property="show_date", type="string", example=false, description="Показывать ли дату"),
 *                  @OA\Property(property="show_time", type="boolean", example=true, description="Показывать ли время"),
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
 *     path="/v2/template/list/{id}",
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
class ApiControllerInternalTemplateList extends ApiController
{
    /**
     * ApiControllerInternalTemplateList constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(TemplatesListModule::setModel(ModelTemplatesList::query()))
            ->setRoles(['administrator', 'subadministrator']);
    }
}
