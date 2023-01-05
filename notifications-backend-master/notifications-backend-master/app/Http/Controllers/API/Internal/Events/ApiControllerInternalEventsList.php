<?php

namespace App\Http\Controllers\API\Internal\Events;

use App\Http\Controllers\API\ApiController;
use App\Models\Events\ModelEventsList;
use App\Modules\Events\EventsListsModule;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApiControllerInternalEventsList
 *
 *
 * @package App\Http\Controllers\API\Events
 */

/**
 * @OA\Tag(
 *      name="Internal Event List",
 *      description="Внутренний компонент для работы с событиями"
 *  )
 *
 * @OA\Get(
 *     path="/v2/events/list",
 *     tags={"Internal Event List"},
 *     summary="Возвращает события с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя организации"),
 *                  @OA\Property(property="hierarchy", type="boolean", example=1, description="Учитывать иерархию"),
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
 *     path="/v2/events/list",
 *     tags={"Internal Event List"},
 *     summary="Создание события.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="hierarchy", type="boolean", example=1, description="Учитывать иерархию"),
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="hierarchy", type="boolean", example=1, description="Учитывать иерархию"),
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
 *     path="/v2/events/list/{id}",
 *     tags={"Internal Event List"},
 *     summary="Обновляем запись события.",
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="hierarchy", type="boolean", example=1, description="Учитывать иерархию"),
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="hierarchy", type="boolean", example=1, description="Учитывать иерархию"),
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
 *     path="/v2/events/list/{id}",
 *     tags={"Internal Event List"},
 *     summary="Возвращает запись события.",
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
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя"),
 *                  @OA\Property(property="hierarchy", type="boolean", example=1, description="Учитывать иерархию"),
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
 *     path="/v2/events/list/{id}",
 *     tags={"Internal Event List"},
 *     summary="Удаляем запись о событии.",
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
class ApiControllerInternalEventsList extends ApiController
{
    /**
     * ApiControllerInternalEventsList constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(EventsListsModule::setModel(ModelEventsList::query()))
            ->setRoles(['administrator', 'subadministrator']);
    }

    public function index(Request $request)
    {
        if (!static::checkAccess()) {
            return $this->sendError('Нет доступ на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->setCode(Response::HTTP_OK);

        $this->setResponse($request->get('type'));
        $this->setPaginator($request->get('paginator'));

        $collect = collect($request->all());

        if (Auth::user()->hasRole('subadministrator')) {
            $data = $collect->get('where', json_encode([]));
            $where = json_decode($data, true);
            $where[] = ['main_organization_id', '=', Auth()->user()->getMainOrganization()];
            $collect->put('where', json_encode($where));
        }

        try {
            $this->getModule()->sql($collect);
        } catch (Exception $exception) {
            return $this->sendError('Error create component (' . $this->getModule()->getNameModule() . '). Message: ' . $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $data = $this->getModule()->getModel()->get()->map(function ($e) use ($request) {
            $e['path_internal'] = !is_null($e->file) ? env('APP_URL') . '/api/v2/file/list/' . $e->file->id . '?type=picture&api_token=' . $request->get('api_token') : '';
            return $e;
        });

        return $this->sendResponse($data->toArray(), $request);
    }
}
