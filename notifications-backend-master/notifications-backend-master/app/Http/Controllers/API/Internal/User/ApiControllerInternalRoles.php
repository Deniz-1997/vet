<?php

namespace App\Http\Controllers\API\Internal\User;

use App\Http\Controllers\API\ApiController;
use App\Modules\User\UsersRoleModule;
use Auth;
use Cache;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

/**
 * Class ApiControllerInternalRoles
 * @package App\Http\Controllers\API\Internal\User
 */
class ApiControllerInternalRoles extends ApiController
{
    /**
     * ApiControllerInternalNotificationsEvent constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(UsersRoleModule::setModel(Role::query()))
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
        if (!static::checkAccess()) {
            return $this->sendError('Нет доступа на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->setResponse($request->get('type'))->setPaginator($request->get('paginator'));

        $collect = collect($request->all());

        if (Auth::user()->hasRole('subadministrator')) {
            $data = $collect->get('where', json_encode([]));
            $where = json_decode($data, true);
            $where[] = ['name', '!=', 'administrator'];
            $collect->put('where', json_encode($where));
        }

        try {
            $this->getModule()->sql($collect);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }

        return $this->sendResponse($this->getModule()->getModel(), $request);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return Response|array|object
     * @throws Exception
     */
    public function show(Request $request, int $id = 0)
    {
        if (!static::checkAccess()) {
            return $this->sendError('Нет доступа на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->setCache(!is_null($request->get('cache')));

        $key_cache = static::getKeyCache($request);

        if ($this->getCache()) {
            $model = Cache::get("cache_model_{$id}_{$key_cache}");

            if (!is_null($model)) {
                return $this->sendResponse($model->toArray(), $request);
            }
        }

        $array = collect($request->all());

        $array->put('id', $id);

        if (Auth::user()->hasRole('subadministrator')) {
            $data = $array->get('where', json_encode([]));
            $where = json_decode($data, true);
            $where[] = ['name', '!=', 'administrator'];
            $array->put('where', json_encode($where));
        }


        try {
            $this->getModule()->sql($array);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }

        if (is_null($request->get('type_data'))) {
            $model = $this->getModule()->getModel()->first();
        } else {
            if (!in_array($request->get('type_data'), ['array', 'first'])) {
                return $this->sendError('Error param type_data. Only array or first', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $method = ($request->get('type_data') === 'array') ? 'get' : 'first';

            $model = $this->getModule()->getModel()->$method();
        }

        /** @var Model|Builder $model */
        if (is_null($model) || $model->count() === 0) {
            return $this->sendError('Not found model');
        }

        if ($this->getCache()) {
            Cache::add("cache_model_{$id}_{$key_cache}", $model, now()->addSeconds(60));
        }

        $this->getModule()->setModel($model);

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
    }

}
