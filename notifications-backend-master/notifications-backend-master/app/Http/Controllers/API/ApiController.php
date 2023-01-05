<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Modules\Module;
use App\Modules\Notifications\NotificationsSendsModule;
use Auth;
use Cache;
use Closure;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

/**
 * Class ApiController
 * @OA\Info(version="1.0.0",title="API Notification center",
 *      description="В документации описаны методы для двух типов обрайщений.
 *      Внутреннее (v2) - Требуются права админстратора.
 *      Внешнее (v1) - Требуются права пользователя.
 *      Внешнее без указания версии API  - Требуются токен канала.
 * Для обращение к api, требуются API токен, который нужно передавать GET параметром api_token={YOUR_TOKEN}",
 * @OA\Contact(email="osok.vadim@gmail.com", name="Vadim"))
 * @OA\Server(url="https://notice.mart-info.ru/api",description="Main host")
 * @OA\SecurityScheme(type="apiKey",in="query",securityScheme="api_key",name="api_token")
 * @package App\Http\Controllers\API
 */
class ApiController extends Controller
{

    /**
     * @var Module
     */
    private $_module;

    /**
     * @var string
     */
    private $_response = 'json';

    /**
     * @var bool
     */
    private $_paginator = false;

    /**
     * @var int
     */
    private $_code = Response::HTTP_OK;

    private $roles = [];

    /**
     * @var bool
     */
    private $_cache;

    /**
     * @var Closure
     */
    private $_callbackPagination;


    /**
     * ApiController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $module
     * @return ApiController
     */
    public function setModuleResources($module)
    {
        $this->_module = $module;

        return $this;
    }

    /**
     * @param array $roles
     * @return ApiController
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param int $code
     * @return void
     */
    public function setCode(int $code)
    {
        $this->_code = $code;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->_code;
    }

    /**
     * @param string|null $type
     * @return ApiController
     */
    public function setResponse($type)
    {
        $this->_response = (is_null($type) || empty($type)) ? 'json' : $type;
        return $this;
    }

    /**
     * @param $paginator
     * @return ApiController
     */
    public function setPaginator($paginator)
    {
        $this->_paginator = ($paginator !== null) ? (int)$paginator : true;
        return $this;
    }

    /**
     * @param bool $cache
     * @return ApiController
     */
    public function setCache(bool $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * @param Closure $callback
     * @return ApiController
     */
    public function setCallbackPagination(Closure $callback)
    {
        $this->_callbackPagination = $callback;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPaginator(): bool
    {
        return $this->_paginator;
    }

    /**
     * @return bool
     */
    public function getCache(): bool
    {
        return $this->_paginator;
    }

    /**
     * @return Module|NotificationsSendsModule
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->_response;
    }

    /**
     * @return Closure
     */
    public function getCallbackPagination(): ?Closure
    {
        return $this->_callbackPagination;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array|Response|object
     * @throws Exception
     */
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

        return $this->sendResponse($this->getModule()->getModel(), $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array|Response|object
     * @throws Exception
     */
    public function store(Request $request)
    {
        if (!static::checkAccess()) {
            return $this->sendError('Нет доступ на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $array = collect(array_merge($request->post(), [
            'created_user_id' => Auth()->user()->id,
            'main_organization_id' => Auth()->user()->getMainOrganization(),
        ]));

        if (is_null($this->getModule())) {
            throw new Exception('Not found module');
        }

        try {
            $this->getModule()->create($array->toArray());
        } catch (ValidationException $exception) {
            return $this->sendError($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            return $this->sendError('Error create component (' . $this->getModule()->getNameModule() . '). Message: ' . $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->setCode(Response::HTTP_CREATED);

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
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
            return $this->sendError('Нет доступ на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->setCode(Response::HTTP_OK);

        $key_cache = $this->getKeyCache($request);

        if ($this->getCache()) {
            $model = Cache::get("cache_model_{$id}_{$key_cache}");

            if (!is_null($model)) {
                return $this->sendResponse($model->toArray(), $request);
            }
        }

        $array = collect($request->all());

        $array->put('id', $id);

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

            $method = ($request->get('type_data') === 'array' || !is_null($request->get('array_model'))) ? 'get' : 'first';

            $model = $this->getModule()->getModel()->$method();
        }

        /** @var Model $model */
        if (is_null($model) || $model->count() === 0) {
            return $this->sendError('Not found model');
        }

        $this->getModule()->setModel($model);

        if ($this->getCache()) {
            Cache::add("cache_model_{$id}_{$key_cache}", $model, now()->addSeconds(60));
        }

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response|array|object
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        if (!static::checkAccess()) {
            return $this->sendError('Нет доступ на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $model = $this->getModule()->getModel()
            ->find($id);

        if (is_null($model)) {
            return $this->sendError('Not found model');
        }

        $this->getModule()->setModel($model);

        $array = collect($request->all());

        try {
            $this->getModule()->update($array->toArray());
        } catch (Exception $exception) {
            return $this->sendError('Error update component (' . $this->getModule()->getNameModule() . '). Message: ' . $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->getModule()->setModel($model);

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response|array|object
     * @throws Exception
     */
    public function destroy(Request $request, int $id)
    {
        if (!static::checkAccess()) {
            return $this->sendError('Нет доступ на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->setCode(Response::HTTP_OK);

        /** @var Model $model */
        $model = $this->getModule()->getModel()->find($id);

        if (is_null($model)) {
            return $this->sendError('Not found record');
        }

        # if exists column deleted user, update him
        if (array_key_exists('deleted_user_id', $model->toArray())) {
            $model->update([
                'deleted_user_id' => Auth()->user()->id
            ]);
        }

        if (array_key_exists('deleted', $model->toArray())) {
            $model->update([
                'deleted' => true
            ]);
        }

        $model->delete();

        return $this->sendResponse(['message' => 'Record success deleted'], $request);
    }

    /**
     * Возвращаем ключ для кеша
     *
     * @param Request $request
     * @return string
     */
    public function getKeyCache(Request $request): string
    {
        $array = collect($request->all());

        $array->forget('api_token');

        $array->forget('cache');

        $array_for_keys = collect();

        $array->map(static function ($e, $k) use ($array_for_keys) {
            $array_for_keys->push("{$k}_{$e}");
        });

        if ($array_for_keys->count() === 0) {
            $array_for_keys->push(str_replace('/', '', $request->path()) . '_' . $request->get('api_token'));
        }

        $start_key = preg_replace('/(\[|]|\"|,|=|\s)/', '_', $array_for_keys->implode('_'));

        return crc32(preg_replace('/_+/', '_', $start_key));
    }

    /**
     * Return success response.
     *
     * @param $objects Model|array|Builder
     * @param Request $request
     * @return array|ResponseFactory|Model|Builder|JsonResponse|Response
     */
    public function sendResponse($objects, Request $request)
    {
        if (!is_null($request->get('body'))) {
            if ($request->get('body') === "no-data") {
                if (!is_null($request->get('cli'))) return $objects;

                if (gettype($objects) == "object") return $this->_return($objects->get());
                return $this->_return($objects);
            }
        }

        if (is_array($objects)) {
            return $this->_return(['status' => true, 'data' => $objects]);
        }

        if ($this->getPaginator()) {

            $pageNumber = $request->get('page');

            Paginator::currentPageResolver(static function () use ($pageNumber) {
                return $pageNumber;
            });

            $key = null;

            if (!is_null($request->get('cache'))) {
                $int = (int)$request->get('cache');
                $key = "cache_pagin_" . $this->getKeyCache($request) . "_" . Auth()->user()->id;

                if ($int === 1) {
                    $data = Cache::get($key);

                    if (!is_null($data)) {
                        return $this->_return($data);
                    }
                } else {
                    Cache::forget($key);
                }
            }

            $data = $objects->paginate($request->get('count'));

            if (!is_null($request->get('cache')) && !is_null($key)) {
                $int = (int)$request->get('cache');

                if ($int === 1) {
                    Cache::add($key, $data, 30);
                }
            }

            if(!is_null($this->getCallbackPagination())){
                $data->getCollection()->transform($this->getCallbackPagination());
            }

            return $this->_return($data);
        }

        return $this->_return([
            'data' => $objects->get(),
            'status' => true
        ]);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return array
     */
    public function sendError($error, $code = 404, $errorMessages = [])
    {
        $response['errors'] = [
            'code' => $code,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors']['data'] = $errorMessages;
        }

        $this->setCode($code);

        $response['status'] = false;

        return $this->_return($response);
    }

    /**
     * Return converted response
     *
     * @param $array
     * @return ResponseFactory|JsonResponse|Response|array
     */
    private function _return($array)
    {
        if (is_null($this->getModule())) {
            return response()->json($array, static::getCode());
        }

        switch ($this->getResponse()) {
            case 'xml':
                return response($array, $this->getCode())->header('Content-Type', 'text/xml');
                break;

            case 'json':
                return response()->json($array, $this->getCode());
                break;

            case '1c':
                return response()->json($array['data'] ?? $array['error'], $this->getCode(), [], 128);
                break;
        }

        return response($array, $this->getCode());
    }

    /**
     * Проверяем доступ к ресурсу
     *
     * @return bool
     */
    public function checkAccess(): bool
    {
        if (count($this->roles) > 0) {
            $access = false;

            foreach ($this->roles as $k => $role) {
                if (Auth::user()->hasRole($role)) {
                    $access = true;
                }
            }

            return $access;
        }

        return true;
    }

    /**
     * Show the form for creating a new resource.
     * @return void
     */
    public function create(): void
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id): void
    {
    }
}
