<?php

namespace App\Http\Controllers\API\External\Notifications;

use App\Exceptions\ValidationException;
use App\Http\Controllers\API\ApiController;
use App\Models\Notifications\ModelNotificationsList;
use App\Modules\DefaultModules;
use App\Traits\TraitValidator;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApiControllerExternalNotificationsReceiver
 *
 * @package App\Http\Controllers\API\External\Notifications
 */
class ApiControllerExternalNotificationsList extends ApiController
{
    use TraitValidator;

    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(DefaultModules::setModel(ModelNotificationsList::query())->setRules([
            'name' => ['required', 'max:255'],
            'event_id' => ['required', 'numeric', 'exists:events.model_events_lists,id'],
            'icon' => ['max:50',
                'in:bell,lightbulb,medkit,fire-extinguisher,hand-holding-medical,heart,hospital,hotjar,biohazard,bolt,bug,bullhorn,burn,calendar,calendar-alt,certificate,check,city,compass,crow,dog,exclamation-circle,exclamation,exclamation-triangle'],
            'status' => ['required', 'in:closed,opened', 'max:255'],
        ]))
            ->setRoles([]);
    }

    /**
     * @OA\Get(path="/notifications/list/",tags={"External Notifications (для сторонних систем)"},
     *     summary="Возвращает созданные оповещения. Требуется ключ канала.",security={{"api_key": {}}},
     * @OA\Response(response=200,description="OK")
     * )
     *
     * @param Request $request
     * @return array|Response|object
     */
    public function index(Request $request)
    {
        $model = ModelNotificationsList::whereChannelId(Auth()->user()->channel_id);

        $this->setPaginator(true);

        return $this->sendResponse($model, $request);
    }

    /**
     * @OA\Post(path="/notifications/list/",tags={"External Notifications (для сторонних систем)"},
     *     summary="Создание оповещения. Требуется ключ канала.",security={{"api_key": {}}},
     *
     *  @OA\RequestBody(required=true,@OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *  @OA\Schema(type="object",
     *      @OA\Property(property="name", type="string", example="АЧС Рузкий район", description="Название"),
     *      @OA\Property(property="event_id", type="integer", example="1", description="ID события"),
     *      @OA\Property(property="status", type="string", example="Открыто", description="Статус оповещения"),
     *  ))),
     *
     *  @OA\Response(response=200,description="OK", @OA\JsonContent(
     *  @OA\Property(property="status", type="boolean"),
     *  @OA\Property(property="message", type="string")
     *  ))))
     *
     * @param Request $request
     * @return array|ResponseFactory|Model|Builder|JsonResponse|Response|object
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $array = collect($request->post());

        try {
            static::$rules = [
                'name' => ['required', 'max:255'],
                'event_id' => ['required', 'numeric', 'exists:events.model_events_lists,id'],
                'icon' => ['max:50',
                    'in:bell,lightbulb,medkit,fire-extinguisher,hand-holding-medical,heart,hospital,hotjar,biohazard,bolt,bug,bullhorn,burn,calendar,calendar-alt,certificate,check,city,compass,crow,dog,exclamation-circle,exclamation,exclamation-triangle'],
                'status' => ['required', 'in:closed,opened', 'max:255'],
            ];
            static::validation($array->toArray());
        } catch (ValidationException $validationException) {
            return $this->sendError($this->validatorMessages());
        } catch (Exception $exception) {
            return $this->sendError('Error server', Response::HTTP_BAD_REQUEST);
        }

        if (is_null($array->get('icon'))) {
            $array->put('icon', 'bell');
        }

        $array->put('channel_id', Auth()->user()->channel_id);

        $model = ModelNotificationsList::create($array->toArray());

        $this->getModule()->setModel($model);

        return $this->sendResponse($this->getModule()->getModel()->toArray(), $request);
    }
}
