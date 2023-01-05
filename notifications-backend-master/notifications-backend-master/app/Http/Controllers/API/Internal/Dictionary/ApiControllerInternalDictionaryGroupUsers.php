<?php

namespace App\Http\Controllers\API\Internal\Dictionary;

use App\Http\Controllers\API\ApiController;
use App\Models\Dictionary\ModelDictionaryGroupUsers;
use App\Modules\Dictionary\DictionaryGroupUsersModule;

/**
 * Class ApiControllerInternalDictionaryGroupUsers
 *
 * @package App\Http\Controllers\API\Internal\Dictionary
 */

/**
 * @OA\Tag(
 *      name="Internal Dictionary User Group",
 *      description="Внутренний компонент для работы с группами пользователей"
 *  )
 *
 * @OA\Get(
 *     path="/v2/dictionary/group-users",
 *     tags={"Internal Dictionary User Group"},
 *     summary="Возвращает группы пользователей с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страницу"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя группы"),
 *                  @OA\Property(property="delay_send", type="integer", example=60),
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
 *     path="/v2/dictionary/group-users",
 *     tags={"Internal Dictionary User Group"},
 *     summary="Создание группы пользователей.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="delay_send", type="integer", example="true"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя группы"),
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя группы"),
 *                  @OA\Property(property="delay_send", type="integer", example=60),
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
 *     path="/v2/dictionary/group-users/{id}",
 *     tags={"Internal Dictionary User Group"},
 *     summary="Обновление записи группе пользователей.",
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
 *                  @OA\Property(property="delay_send", type="integer", example="true"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя группы"),
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя группы"),
 *                  @OA\Property(property="delay_send", type="integer", example=60),
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
 * @OA\Get(
 *     path="/v2/dictionary/group-users/{id}",
 *     tags={"Internal Dictionary User Group"},
 *     summary="Возвращает группы пользователей.",
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя группы"),
 *                  @OA\Property(property="delay_send", type="integer", example=60),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
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
 *     path="/v2/dictionary/group-users/{id}",
 *     tags={"Internal Dictionary User Group"},
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
class ApiControllerInternalDictionaryGroupUsers extends ApiController
{
    /**
     * ApiControllerDictionaryCallLang constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(DictionaryGroupUsersModule::setModel(ModelDictionaryGroupUsers::query()))
            ->setRoles(['administrator', 'subadministrator']);
    }
}
