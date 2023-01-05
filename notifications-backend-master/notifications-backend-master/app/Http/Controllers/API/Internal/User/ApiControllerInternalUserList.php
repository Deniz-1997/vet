<?php

namespace App\Http\Controllers\API\Internal\User;

use App\Components\PhoneComponents;
use App\Exceptions\ValidationException;
use App\Http\Controllers\API\ApiController;
use App\Models\User\ModelUserGroups;
use App\Modules\User\UsersListModule;
use App\Traits\TraitValidator;
use App\User;
use Auth;
use Exception;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use libphonenumber\NumberParseException;
use Spatie\Permission\Models\Role;
use Validator;

/**
 * Class ApiControllerInternalUserList
 *
 * @package App\Http\Controllers\API\Dictionary
 */

/**
 * @OA\Tag(
 *      name="Internal User List",
 *      description="Внутренний компонент для работы с пользователями"
 *  )
 *
 * @OA\Get(
 *     path="/v2/user/list",
 *     tags={"Internal User List"},
 *     summary="Возвращает пользователей с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="email", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="name", type="integer", example=1, description="ID организации"),
 *                  @OA\Property(property="api_token", type="integer", example=1, description="ID подорганизации"),
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
 *
 * @OA\Post(
 *     path="/v2/user/list",
 *     tags={"Internal User List"},
 *     summary="Создание записи об девайсе.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="groups", type="array", @OA\Items(), description="Группы, к которым относится пользователь"),
 *                  @OA\Property(property="name", type="string", example="Test", description="ID пользователя"),
 *                  @OA\Property(property="email", type="string", example="test@gmail.com", description="Почта"),
 *                  @OA\Property(property="password", type="string", example="3243423", description="Пароль"),
 *                  @OA\Property(property="phone", type="string", example="79991110022", description="Номер телефона"),
 *                  @OA\Property(property="organization_id", type="integer", example=1, description="К какой организации относится пользователь"),
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
 *                  @OA\Property(property="email", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="name", type="integer", example=1, description="ID организации"),
 *                  @OA\Property(property="api_token", type="integer", example=1, description="ID подорганизации"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 *
 * @OA\Put(
 *     path="/v2/user/list/{id}",
 *     tags={"Internal User List"},
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
 *                  @OA\Property(property="groups", type="array", @OA\Items(), description="Группы, к которым относится пользователь"),
 *                  @OA\Property(property="name", type="string", example="Test", description="ID пользователя"),
 *                  @OA\Property(property="email", type="string", example="test@gmail.com", description="Почта"),
 *                  @OA\Property(property="password", type="string", example="3243423", description="Пароль"),
 *                  @OA\Property(property="phone", type="string", example="79991110022", description="Номер телефона"),
 *                  @OA\Property(property="organization_id", type="integer", example=1, description="К какой организации относится пользователь"),
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
 *                  @OA\Property(property="email", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="name", type="integer", example=1, description="ID организации"),
 *                  @OA\Property(property="api_token", type="integer", example=1, description="ID подорганизации"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Get(
 *     path="/v2/user/list/{id}",
 *     tags={"Internal User List"},
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
 *                  @OA\Property(property="email", type="integer", example=1, description="ID пользователя"),
 *                  @OA\Property(property="name", type="integer", example=1, description="ID организации"),
 *                  @OA\Property(property="api_token", type="integer", example=1, description="ID подорганизации"),
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
 *     path="/v2/user/list/{id}",
 *     tags={"Internal User List"},
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
class ApiControllerInternalUserList extends ApiController
{
    use TraitValidator;

    /**
     * @var User
     */
    private $_model;

    /**
     * ApiControllerInternalNotificationsEvent constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(UsersListModule::setModel(User::query()))
            ->setRoles(['administrator', 'subadministrator']);
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
        if (!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('subadministrator')) {
            return $this->sendError('Доступ запрещен.', Response::HTTP_FORBIDDEN);
        }

        $array = collect($request->post());

        try {
            static::$rules = [
                'email' => ['required', 'string', 'email', 'unique:users,email'],
                'phone' => ['string', 'max:255', 'unique:users,phone'],
                'name' => ['required', 'min:3', 'max:255', 'unique:users,name']
            ];

            static::validation($array->toArray());
        } catch (ValidationException $validationException) {
            # если данные не прошли валидацию
            return $this->sendError($this->validatorMessages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            return $this->sendError('Error create component (' . $this->getModule()->getModel()->getTable() . '). Message: ' . $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $array->put('main_organization_id', Auth()->user()->getMainOrganization());

        $until = new PhoneComponents();

        try {
            $phone = $until->parse($array->get('phone'));
            $array->put('phone', $phone);
        } catch (NumberParseException $exception) {
            return response()->json([
                'status' => false,
                'msg' => 'Некорректный формат номера телефона'
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->_model = User::create($array->toArray());

        $this->getModule()->setModel($this->_model);

        if (!is_null($array->get('groups'))) {
            $this->_saveGroup(collect($array->get('groups')));
        }

        if (!is_null($array->get('roles'))) {
            $this->_saveRole(collect($array->get('roles')));
        }

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array|Response|object
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('subadministrator')) {
            return $this->sendError('Нет доступа на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        $this->_model = $this->getModule()->getModel()->whereId($id)->with(['groups'])->first();

        if (is_null($this->_model)) {
            return $this->sendError('Not found model');
        }

        $array = collect($request->all());

        if (!is_null($array->get('password')) && !Hash::check($array->get('password'), $this->_model->password)) {
            $this->_model->password = Hash::make($array->get('password'));
        }

        if (!is_null($array->get('email')) && $this->_model->email !== $array->get('email')) {
            $this->_model->email = $array->get('email');
        }

        if (!is_null($array->get('phone')) && $this->_model->phone !== $array->get('phone')) {

            $until = new PhoneComponents();

            try {
                $phone = $until->parse($array->get('phone'));
                $array->put('phone', $phone);
            } catch (NumberParseException $exception) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Некорректный формат номера телефона'
                ], Response::HTTP_BAD_REQUEST);
            }

            $this->_model->phone = $array->get('phone');
        }

        if (!is_null($array->get('name')) && $this->_model->name !== $array->get('name')) {
            $this->_model->name = $array->get('name');
        }

        if (!is_null($array->get('organization_id')) && $this->_model->organization_id !== $array->get('organization_id')) {

            $validator = Validator::make($array->toArray(), [
                'organization_id' => ['required', 'numeric', 'exists:dictionary.model_dictionary_organizations,id'],
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->messages(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $this->_model->organization_id = $array->get('organization_id');
        }

        $this->_saveGroup(collect($array->get('groups')));

        $this->_saveRole(collect($array->get('roles')));

        $this->_model->save();

        return $this->sendResponse($this->_model->toArray(), $request);
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
        if (!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('subadministrator')) {
            return $this->sendError('Нет доступа на выполнение задачи.', Response::HTTP_FORBIDDEN);
        }

        if ($id === Auth::user()->id) {
            return $this->sendError('Вы не можете удалить авторизированого пользователя.', Response::HTTP_BAD_REQUEST);
        }

        /** @var Model|Builder $model */
        $model = $this->getModule()->getModel()->find($id);

        if (is_null($model)) {
            return $this->sendError('Not found record');
        }

        $model->delete();

        return $this->sendResponse(['message' => 'Record success deleted'], $request);
    }

    /**
     * Удаляем или сохраняем группу, в которую входит пользователь, сравнивая массивы
     *
     * @param Collection $collection
     * @throws Exception
     */
    private function _saveGroup(Collection $collection)
    {
        $currentArray = collect($this->_model->groups)->map(function ($e) {
            return $e->group_id;
        });

        $newArray = $collection->map(function ($e) {
            if (is_numeric($e)) {
                return $e;
            } elseif (is_array($e)) {
                return $e['group_id'];
            } else {
                return $e->group_id;
            }
        });

        $addArray = $newArray->diff($currentArray);

        $addArray->map(function ($e) {
            ModelUserGroups::create([
                'user_id' => $this->_model->id,
                'group_id' => $e
            ]);
        });

        $removeArray = $currentArray->diff($newArray->toArray());

        $removeArray->map(function ($e) {
            $model = ModelUserGroups::whereUserId($this->_model->id)
                ->whereGroupId($e)
                ->first();

            if (!is_null($model)) {
                $model->delete();
            }
        });
    }

    /**
     * Удаляем или сохраняем роль, в которую входит пользователь, сравнивая массивы
     *
     * @param Collection $collection
     * @throws Exception
     */
    private function _saveRole(Collection $collection)
    {
        $currentArray = collect($this->_model->roles)->map(function ($e) {
            return $e->id;
        });

        $newArray = $collection->map(function ($e) {
            if (is_numeric($e)) {
                return $e;
            } elseif (is_array($e)) {
                return $e['id'];
            } else {
                return $e->id;
            }
        });

        $addArray = $newArray->diff($currentArray);

        $addArray->map(function ($e) {
            $role_ = Role::whereId($e)->first();

            if (is_null($role_)) {
                throw new Exception('Not found role by ' . $e);
            }

            $this->_model->assignRole($role_);
        });

        $removeArray = $currentArray->diff($newArray->toArray());

        $removeArray->map(function ($e) {
            $role_ = Role::whereId($e)->first();

            if (is_null($role_)) {
                throw new Exception('Not found role by ' . $e);
            }

            $this->_model->removeRole($role_);
        });
    }
}
