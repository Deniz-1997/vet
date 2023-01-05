<?php

namespace App\Http\Controllers\API\Internal\Dictionary;

use App\Http\Controllers\API\ApiController;
use App\Models\Dictionary\ModelDictionaryHistoriesType;
use App\Modules\Dictionary\DictionaryHistoriesTypeModule;

/**
 * Class ApiControllerInternalDictionaryHistoriesType
 *
 * @package App\Http\Controllers\API\Internal\Dictionary
 */

/**
 * @OA\Tag(
 *      name="Internal Dictionary Histories Type",
 *      description="Внутренний компонент для работы с типами историй"
 *  )
 *
 * @OA\Get(
 *     path="/v2/dictionary/histories-type",
 *     tags={"Internal Dictionary Histories Type"},
 *     summary="Возвращает тип историй с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя истории"),
 *                  @OA\Property(property="template", type="string", example="Test", description="Шаблон истории"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было обновлено оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было удалено оповещение"),
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
 *     path="/v2/dictionary/histories-type",
 *     tags={"Internal Dictionary Histories Type"},
 *     summary="Создание типа историй.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя истории"),
 *                  @OA\Property(property="template", type="string", example="Test", description="Шаблон истории"),
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя истории"),
 *                  @OA\Property(property="template", type="string", example="Test", description="Шаблон истории"),
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
 *     path="/v2/dictionary/histories-type/{id}",
 *     tags={"Internal Dictionary Histories Type"},
 *     summary="Обновляем запись типа истории.",
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя истории"),
 *                  @OA\Property(property="template", type="string", example="Test", description="Шаблон истории"),
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя истории"),
 *                  @OA\Property(property="template", type="string", example="Test", description="Шаблон истории"),
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
 *     path="/v2/dictionary/histories-type/{id}",
 *     tags={"Internal Dictionary Histories Type"},
 *     summary="Возвращает тип истории.",
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя истории"),
 *                  @OA\Property(property="template", type="string", example="Test", description="Шаблон истории"),
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
 *     path="/v2/dictionary/histories-type/{id}",
 *     tags={"Internal Dictionary Histories Type"},
 *     summary="Удаляем тип истории.",
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
class ApiControllerInternalDictionaryHistoriesType extends ApiController
{
    /**
     * ApiControllerDictionaryCallLang constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(DictionaryHistoriesTypeModule::setModel(ModelDictionaryHistoriesType::query()))
            ->setRoles(['administrator']);
    }
}
