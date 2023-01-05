<?php

namespace App\Http\Controllers\API\Internal\User;

use App\Http\Controllers\API\ApiController;
use App\Models\User\ModelUserDevices;
use App\Modules\User\UsersDevicesModule;

/**
 * Class ApiControllerInternalUserDevices
 * @package App\Http\Controllers\API\Internal\User
 */

/**
 * @OA\Tag(
 *      name="Internal User Devices",
 *      description="Внутренний компонент для работы с информаций по девайсам для пользователей"
 *  )
 *
 * @OA\Get(
 *     path="/v2/user/devices",
 *     tags={"Internal User Devices"},
 *     summary="Возвращает информацию девайса пользователя с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="device", type="string", example="android or iphone", description="Имя девайса"),
 *                  @OA\Property(property="token", type="string", description="Токен девайса"),
 *                  @OA\Property(property="reg_id", type="string", description="ИД девайса"),
 *                  @OA\Property(property="access_key", type="string", description="Ключ девайса"),
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
 *     path="/v2/user/devices",
 *     tags={"Internal User Devices"},
 *     summary="Создание записи о девайсе.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="device", type="string", example="android or iphone", description="Имя девайса"),
 *                  @OA\Property(property="token", type="string", description="Токен девайса"),
 *                  @OA\Property(property="reg_id", type="string", description="ИД девайса"),
 *                  @OA\Property(property="access_key", type="string", description="Ключ девайса"),
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
 *                  @OA\Property(property="device", type="string", example="android or iphone", description="Имя девайса"),
 *                  @OA\Property(property="token", type="string", description="Токен девайса"),
 *                  @OA\Property(property="reg_id", type="string", description="ИД девайса"),
 *                  @OA\Property(property="access_key", type="string", description="Ключ девайса"),
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
 *     path="/v2/user/devices/{id}",
 *     tags={"Internal User Devices"},
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
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="device", type="string", example="android or iphone", description="Имя девайса"),
 *                  @OA\Property(property="token", type="string", description="Токен девайса"),
 *                  @OA\Property(property="reg_id", type="string", description="ИД девайса"),
 *                  @OA\Property(property="access_key", type="string", description="Ключ девайса"),
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
 *                  @OA\Property(property="device", type="string", example="android or iphone", description="Имя девайса"),
 *                  @OA\Property(property="token", type="string", description="Токен девайса"),
 *                  @OA\Property(property="reg_id", type="string", description="ИД девайса"),
 *                  @OA\Property(property="access_key", type="string", description="Ключ девайса"),
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
 *     path="/v2/user/devices/{id}",
 *     tags={"Internal User Devices"},
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
 *                  @OA\Property(property="device", type="string", example="android or iphone", description="Имя девайса"),
 *                  @OA\Property(property="token", type="string", description="Токен девайса"),
 *                  @OA\Property(property="reg_id", type="string", description="ИД девайса"),
 *                  @OA\Property(property="access_key", type="string", description="Ключ девайса"),
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
 *     path="/v2/user/devices/{id}",
 *     tags={"Internal User Devices"},
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
class ApiControllerInternalUserDevices extends ApiController
{
    /**
     * ApiControllerInternalNotificationsEvent constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(UsersDevicesModule::setModel(ModelUserDevices::query()))
            ->setRoles(['administrator', 'subadministrator']);
    }
}
