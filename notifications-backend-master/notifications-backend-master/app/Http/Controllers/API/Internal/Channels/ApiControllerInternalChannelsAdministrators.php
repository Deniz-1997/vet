<?php

namespace App\Http\Controllers\API\Internal\Channels;

use App\Http\Controllers\API\ApiController;
use App\Models\Channels\ModelChannelsAdministrators;
use App\Modules\Channels\ChannelsAdministratorsModule;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApiControllerInternalChannelsAdministrators
 *
 *
 * @package App\Http\Controllers\API\Internal\Channels
 */

/**
 * @OA\Tag(
 *      name="Internal Channels Administrator",
 *      description="Внутренний компонент для работы с администраторами канала"
 *  )
 *
 * @OA\Get(
 *     path="/v2/channels/administrators",
 *     tags={"Internal Channels Administrator"},
 *     summary="Возвращает администраторов канала пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="channel_id", type="integer", example=1, description="ID канала"),
 *                  @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя"),
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
 *     path="/v2/channels/administrators",
 *     tags={"Internal Channels Administrator"},
 *     summary="Создание администраторов канала.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="channel_id", type="integer", example="true", description="ID канала"),
 *                  @OA\Property(property="user_id", type="integer", example="Test", description="ID пользователя"),
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
 * @OA\Put(
 *     path="/v2/channels/administrators/{id}",
 *     tags={"Internal Channels Administrator"},
 *     summary="Обновляем запись об администраторе канала.",
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
 *                  @OA\Property(property="user_id", type="integer", example="Test", description="ID пользователя"),
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
 *     path="/v2/channels/administrators/{id}",
 *     tags={"Internal Channels Administrator"},
 *     summary="Возвращает оповещения конкретного администратора канала.",
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
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Delete(
 *     path="/v2/channels/administrators/{id}",
 *     tags={"Internal Channels Administrator"},
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
class ApiControllerInternalChannelsAdministrators extends ApiController
{
    /**
     * ApiControllerInternalChannelsAdministrators constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(ChannelsAdministratorsModule::setModel(ModelChannelsAdministrators::query()))
            ->setRoles(['administrator', 'subadministrator']);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array|Response|object
     */
    public function index(Request $request)
    {
        if (!$this->checkAccess()) {
            return $this->sendError('Нет доступа на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->setResponse($request->get('type'))->setPaginator($request->get('paginator'));

        $collect = collect($request->all());

        if (Auth::user()->hasRole('subadministrator')) {
            $data = (is_null($collect->get('where'))) ? [] : $collect->get('where');
            $where = json_decode($data, true);
            $where[] = ['user_id', '=', Auth()->user()->id];
            $collect->put('where', json_encode($where));
        }

        try {
            $this->getModule()->sql($collect);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }

        return $this->sendResponse($this->getModule()->getModel(), $request);
    }
}
