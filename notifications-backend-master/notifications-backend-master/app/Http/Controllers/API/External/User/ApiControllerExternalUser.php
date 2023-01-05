<?php

namespace App\Http\Controllers\API\External\User;

use App\Components\PhoneComponents;
use App\Exceptions\ValidationException;
use App\Http\Controllers\API\ApiController;
use App\Models\User\ModelUserDevices;
use App\Models\User\ModelUserNotificationOptions;
use App\Models\User\ModelUserSmsCode;
use App\ModelUserTokenByDevice;
use App\Modules\User\UsersSmsCodesModule;
use App\Traits\TraitValidator;
use App\User;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use libphonenumber\NumberParseException;

/**
 * Class ApiControllerExternalUser
 * @OA\Tag(
 *      name="External User",
 *      description="Внешний компонент для работы с пользователями"
 *  )
 * @package App\Http\Controllers\API\External\User
 */
class ApiControllerExternalUser extends ApiController
{
    use TraitValidator;

    /**
     * Тестовый телефон для входа в систему, по умолчани код для него 1234
     *
     * @var string
     */
    private $_testPhone = "+79001234567";

    /**
     * @OA\Post(path="/user/auth",tags={"External User"},summary="Authentication user",
     *      @OA\RequestBody(required=true,
     *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(type="object",required={"phone","password"},
     *                  @OA\Property(property="phone",description="Номер телефона пользователя",type="string"),
     *                  @OA\Property(property="device_id",description="Device ID",type="integer")
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200,description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean"),
     *              @OA\Property(property="hash", description="Хеш для проверки кода который будет отправлен на номер телефона", example="@#TAGh8aSF0asfjspfijaspijeopgOIASHGOUIASGHf", type="string"),
     *              @OA\Property(property="link", description="Ссылка для проверки кода", example="/api/sms/check/{HASH}", type="string"),
     *              @OA\Property(property="msg", example="Параметр появится, если запросить код повторно не подождав 5 минут", type="string"),
     *          )
     *      ),
     *      @OA\Response(response=400,description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean"),
     *              @OA\Property(property="code", type="integer"),
     *              @OA\Property(property="message", description="Сообщение об ошибке", type="string")
     *          )
     *      ),
     *      @OA\Response(response=500,description="Error server")
     * )
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function auth(Request $request): JsonResponse
    {
        static::$rules = [
            'phone' => ['required'],
            'device_id' => ['required'],
        ];

        static::validation($request->post());

        $phone = $request->get('phone');

        if (preg_replace('/\+/', '', $phone) === '+7001234567') {
            $until = new PhoneComponents();

            try {
                $phone = $until->parse($phone);
            } catch (NumberParseException $exception) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Некорректный формат номера телефона'
                ], Response::HTTP_BAD_REQUEST);
            } catch (Exception $exception) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Ошибка сервер при конвертирование номера'
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        $model = User::wherePhone($phone)->first();

        if (is_null($model)
//            || !$model->hasRole('user')
        ) {
            return response()->json(['status' => false, 'msg' => 'Invalid phone'], Response::HTTP_BAD_REQUEST);
        }

        $device = ModelUserTokenByDevice::firstOrCreate([
            'user_id' => $model->id,
            'device_id' => $request->get('device_id')
        ]);

        $userSmsCode = UsersSmsCodesModule::setModel(ModelUserSmsCode::query());

        if (!$userSmsCode::checkLimit($model->id, $device->id)) {
            $modelSmsCode = $userSmsCode::getModel()
                ->whereDeviceId($device->id)
                ->whereCompleted(false)
                ->whereUserId($model->id)->first();

            $second = 60 * 5 - (Carbon::now()->timestamp - Carbon::parse($modelSmsCode->created_at)->timestamp);

            return response()->json([
                'status' => true,
                'msg' => "Повторная отправка кода возможна через $second сек",
                'hash' => $modelSmsCode->hash,
                'link' => env('APP_URL') . "/api/sms/check/{$modelSmsCode->hash}"
            ], Response::HTTP_OK);
        }

        $userSmsCode::completedRecords($model->id, $device->id);

        $modelSmsCode = $userSmsCode::create([
            'user_id' => $model->id,
            'phone' => $model->phone === $this->_testPhone ? null : $model->phone,
            'device_id' => $device->id
        ]);

        return response()->json([
            'status' => true,
            'hash' => $modelSmsCode::getModel()->hash,
            'link' => env('APP_URL') . "/api/sms/check/{$modelSmsCode::getModel()->hash}"
        ], Response::HTTP_OK);
    }

    /**
     * Деактивация токена пользователя
     *
     * @OA\Post(path="/v1/user/logout",tags={"External User"},summary="Аннулируется токен пользователя",security={{"api_key": {}}},
     *      @OA\Response(response=200,description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean"),
     *              @OA\Property(property="message", example="Success logout", type="string"),
     *          )
     *      ),
     *      @OA\Response(response=400,description="Bad request"),
     *      @OA\Response(response=500,description="Error server")
     *  )
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function logout(): JsonResponse
    {
        /** @var Model $user */
        $user = Auth()->user();

        $user->delete();

        return response()->json(['status' => true, 'message' => 'Success logout.'], Response::HTTP_OK);
    }

    /**
     * @OA\Get(path="/v1/user/",tags={"External User"},summary="Возвращает информацию пользователя.",security={{"api_key": {}}},
     *  @OA\Response(response=200,description="OK",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="boolean"),
     *          @OA\Property(property="message", type="string"),
     *          @OA\Property(property="data", type="object",
     *              @OA\Property(property="name", type="string", example="Albert Efimov", description="Имя пользователя"),
     *              @OA\Property(property="email", type="string", example="test@mail.ru", description="Почта пользователя"),
     *              @OA\Property(property="phone", type="integer", example="79839990099", description="Номер телефона пользователя"),
     *              @OA\Property(property="organizations", type="string", example="Руководство Ветеринарии", description="В какой организации состоит пользователь"),
     *          )
     *      )
     *  ),
     *  @OA\Response(response=400,description="Bad request"),
     *  @OA\Response(response=500,description="Error server")
     * )
     *
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        /** @var User $user */
        $user = Auth()->user()->originalUser();
        return response()->json($this->getInfo($user), Response::HTTP_OK);
    }

    /**
     * @OA\Post(path="/v1/user/info",tags={"External User"},summary="Обновление информации пользователя.",security={{"api_key": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="phone", type="string", example="+79899999999", description="Номер телефона"),
     *                  @OA\Property(property="password", type="string", example="Test Name", description="Пароль"),
     *                  @OA\Property(property="name", type="string", example="Test Name", description="Имя пользователя"),
     *                  @OA\Property(property="email", type="string", example="test@mail.ru", description="Почта пользователя"),
     *                  @OA\Property(property="notifications_type", type="string", example="scheduled|alwaysEnabled|alwaysDisabled", description="Тип оповещения"),
     *                  @OA\Property(property="notifications_time_from", type="string", example="00:00", description="Время оповещения С"),
     *                  @OA\Property(property="notifications_time_to", type="string", example="00:00", description="Время оповещения ДО"),
     *                  @OA\Property(property="device_platform",type="object",description="Данные о девайсе пользователя",
     *                      @OA\Property(property="platform",type="string",default="android",example="android or ios"),
     *                      @OA\Property(property="token",type="string",example="CL3utSDSvJT1Lj34QtYvF1")
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=1, description="Статус"),
     *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
     *                  @OA\Property(property="name", type="string", example="Albert Efimov", description="Имя пользователя"),
     *                  @OA\Property(property="email", type="string", example="test@mail.ru", description="Почта пользователя"),
     *                  @OA\Property(property="phone", type="integer", example="79839990099", description="Номер телефона пользователя"),
     *                  @OA\Property(property="organizations", type="string", example="Руководство Ветеринарии", description="В какой организации состоит пользователь"),
     *                  )
     *              ),
     *          )
     *     ),
     *      @OA\Response(response=400,description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean"),
     *              @OA\Property(property="code", type="integer"),
     *              @OA\Property(property="message", description="Сообщение об ошибке", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error server",
     *      )
     *  )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateInfo(Request $request)
    {
        $array = collect($request->post());

        /** @var User $user */
        $user = Auth()->user()->originalUser();

        try {

            $modelUserNotificationOptions = ModelUserNotificationOptions::whereUserId($user->id)->whereDeviceId(Auth()->user()->id)->first();


            if (is_null($modelUserNotificationOptions)) {
                $modelUserNotificationOptions = ModelUserNotificationOptions::firstOrCreate([
                    'user_id' => $user->id,
                    'device_id' => Auth()->user()->id,
                    'type' => $array->get('notifications_type') ?? ModelUserNotificationOptions::ENABLED,
                ]);

                $modelUserNotificationOptions->type = ModelUserNotificationOptions::ENABLED;
            }

            if (!is_null($array->get('password')) && !empty($array->get('password')) && !Hash::check($array->get('password'), $user->password)) {
                $user->password = Hash::make($array->get('password'));
            }

            if (!is_null($array->get('email')) && !empty($array->get('email')) && trim($array->get('email')) !== $user->email) {
                static::$rules = ['email' => ['required', 'unique:users,email']];
                static::validation($array->toArray());
                $user->email = strtolower($array->get('email'));
            }

            if (!is_null($array->get('name')) && !empty($array->get('name')) && trim($array->get('name')) !== $user->name) {
                static::$rules = ['name' => ['required', 'max:255']];
                static::validation($array->toArray());
                $user->name = $array->get('name');
            }

            if (!is_null($array->get('phone')) && !empty($array->get('phone')) && trim($array->get('phone')) !== $user->phone) {
                static::$rules = ['phone' => ['required', 'max:15', 'unique:model_info_users,phone']];
                static::validation($array->toArray());
                $user->phone = preg_replace('/(-|\s|\s+|\(|\))/', '', $array->get('phone'));
            }

            if (!is_null($array->get('notifications_type'))) {
                if ($array->get('notifications_type') === ModelUserNotificationOptions::SCHEDULED && is_null($array->get('notifications_time_from')) && is_null($array->get('notifications_time_to'))) {
                    return response()->json(['status' => false, 'msg' => 'Пожалуйста, укажите time_from и time_to.'], Response::HTTP_BAD_REQUEST);
                }

                if ($modelUserNotificationOptions->type !== $array->get('notifications_type')) {
                    $modelUserNotificationOptions->type = $array->get('notifications_type');
                }
            }

            if (!is_null($modelUserNotificationOptions)) {
                if ($modelUserNotificationOptions->type === ModelUserNotificationOptions::SCHEDULED) {

                    if (!is_null($array->get('notifications_time_from'))) {
                        $modelUserNotificationOptions->from_time = $array->get('notifications_time_from');
                    }

                    if (!is_null($array->get('notifications_time_to'))) {
                        $modelUserNotificationOptions->to_time = $array->get('notifications_time_to');
                    }
                }
            }

            if (!is_null($array->get('device_platform'))) {
                $device = $array->get('device_platform');
//                \Log::warning(json_encode($device));
                static::$rules = [
                    'platform' => ['required', 'max:255'],
                    'device_id' => ['string'],
                    'token' => ['required']
                ];

                static::validation($device);

                $platform = strtolower($device['platform']);
//                \Log::warning(json_encode($platform));

                $userDeviceModel = ModelUserDevices::whereDevice($platform)
                    ->whereToken($device['token'])->first();

                if (!is_null($userDeviceModel)) {
                    $userDeviceModel->user_id = Auth()->user()->originalUser()->id;
                    $userDeviceModel->device_id = Auth()->user()->id;
                    $userDeviceModel->save();
                } else {
                    ModelUserDevices::firstOrCreate([
                        'reg_id' => 'unknown',
                        'token' => $device['token'],
                        'device_id' => Auth()->user()->id,
                        'user_id' => Auth()->user()->originalUser()->id,
                        'device' => $platform
                    ]);
                }

            }
            $modelUserNotificationOptions->save();
            $user->save();
        } catch (ValidationException $exception) {
            return response()->json(['status' => false, 'msg' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return response()->json(['status' => false,
                'data' => env('APP_DEBUG') ? $exception->getMessage() : null,
                'msg' => 'Произошла ошибка на стороне сервера.'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($this->getInfo($user), Response::HTTP_OK);
    }

    /**
     * @param User $user
     * @return array
     */
    private function getInfo($user)
    {
        $org = $user->organization;
        return [
            'status' => true,
            'message' => 'Success get info',
            'data' => collect([
                'name' => $user->name,
                'email' => $user->email,
                'organization' => is_null($org) ? 'Не найдено' : $org->name,
                'phone' => $user->phone,
                'groups' => $user->groups->map(function ($e){
                    return $e->id;
                }),
            ])
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/sms/check/{hash}",
     *     tags={"External User"},
     *     summary="Проверка кода, отправленного на номер пользователя",
     *     security={{"api_key": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="code", type="integer", example="0505", description="Код"),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=1, description="Статус"),
     *              @OA\Property(property="token", type="string", description="Токен для работы с системой"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error server",
     *     )
     * )
     *
     * @param Request $request
     * @param string $hash
     * @return JsonResponse
     */
    public function smsCheck(Request $request, string $hash)
    {
        $modelSmsCode = ModelUserSmsCode::whereHash($hash)->whereCompleted(false)->first();

        if (is_null($modelSmsCode)) {
            return response()->json(['status' => false, 'code' => '20453', 'msg' => 'Запись не найдена'], Response::HTTP_NOT_FOUND);
        }

        if (is_null($request->get('code'))) {
            return response()->json(['status' => false, 'code' => '43524', 'msg' => 'Добавьте код из смс'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $code = preg_replace('/\s+/', '', $request->get('code'));

        if ($code != $modelSmsCode->code) {
            return response()->json(['status' => false, 'code' => '53240', 'msg' => 'Некорректно указан код из смс'],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $model = User::find($modelSmsCode->user_id);

        if (is_null($model)) {
            return response()->json(['status' => false, 'code' => '34250', 'msg' => 'Пользователь не найден'], Response::HTTP_NOT_FOUND);
        }

        $modelSmsCode->completed = true;

        $modelSmsCode->save();

        $device = ModelUserTokenByDevice::find($modelSmsCode->device_id);

        return response()->json(['status' => true, 'token' => $device->api_token], Response::HTTP_OK);
    }
}
