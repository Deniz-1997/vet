<?php


namespace App\Http\Controllers\API\External\Notifications;


use App\Exceptions\ValidationException;
use App\Http\Controllers\API\ApiController;
use App\Jobs\JobSendNotifications;
use App\Logger;
use App\Models\Channels\ModelChannelsEvent;
use App\Models\Channels\ModelChannelsUsers;
use App\Models\Dictionary\ModelDictionaryOrganizations;
use App\Models\Events\ModelEventsList;
use App\Models\Notifications\ModelNotificationsEvents;
use App\Models\Notifications\ModelNotificationsList;
use App\Models\Notifications\ModelNotificationsSend;
use App\Models\Templates\ModelTemplatesGroupUser;
use App\Modules\DefaultModules;
use App\Modules\Notifications\NotificationsSendsModule;
use App\Traits\TraitValidator;
use App\User;
use Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

/**
 * Class ApiControllerExternalNotificationsReceiver
 *
 * @OA\Tag(
 *      name="External Notifications (для сторонних систем)",
 *      description="Компонент для работы с оповещениями со стороны сторонних сервисов"
 *  )
 *
 * @package App\Http\Controllers\API\External\Notifications
 */
class ApiControllerExternalNotificationsReceiver extends ApiController
{
    use TraitValidator;

    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(NotificationsSendsModule::setModel(ModelNotificationsSend::query())->setRules([]))
            ->setRoles([]);
    }

    /**
     * @var Collection
     */
    private $users;

    /**
     * @OA\Post(path="/notifications/receiver/",tags={"External Notifications (для сторонних систем)"},
     *     summary="Отправка оповещений от стороних систем. Требуется ключ канала.",security={{"api_key": {}}},
     *
     *  @OA\RequestBody(required=true,@OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *  @OA\Schema(type="object",
     *      @OA\Property(property="organization_id", type="integer", description="ID организации"),
     *      @OA\Property(property="notify_id", type="integer", example="1", description="ID оповещения"),
     *      @OA\Property(property="text", type="string", example="Текст оповещения", description="Текст оповещения"),
     *  ))),
     *
     *  @OA\Response(response=200,description="OK", @OA\JsonContent(
     *  @OA\Property(property="status", type="boolean"),
     *  @OA\Property(property="message", type="string")
     *  ))),
     * @OA\Response(response=400,description="Bad request"),
     * @OA\Response(response=500,description="Error server"))
     *
     * @param Request $request
     * @return array|Response|object
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        static::$rules = [
            'text' => ['required', 'string'],
            'organization_id' => ['required', 'numeric', 'exists:dictionary.model_dictionary_organizations,id'],
            'notify_id' => ['required', 'numeric', 'exists:notifications.model_notifications_lists,id'],
            'good_notification' => ['boolean'],
        ];

        try {
            Logger::debug('Validation');
            static::validation($request->toArray());

            Logger::debug('Validation success');
            $collection = collect($request->post())->put('channel_id', Auth()->user()->channel_id);
            Logger::debug(json_encode($collection));

            $this->getModule()
                ->validationSendNotify($collection)
                ->setUsersOfChannel((int)$collection->get('channel_id'))
                ->setParentOrganizations((int)$collection->get('organization_id'))
                ->setTemplate()
                ->convertUsersForNotifications();

            if ($this->getModule()->getUsersForNotifications()->count() > 0) {
                $array_for_events = ['text' => $request->post('text')];

                if (!is_null($request->post('good_notification'))) {
                    $array_for_events['good_emoji'] = $request->post('good_notification');
                }

                $users_temp = $this->getModule()->sendNotifications($array_for_events);

                if ($users_temp->count() === 0) {
                    return response()->json([
                        'status' => false,
                        'data' => collect([
                            'message' => 'Нет пользователей для отправки уведомлений',
                        ])
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'data' => collect([
                        'message' => "Уведомление было отправлено {$users_temp->count()} пользователю(ям)",
                        'notifications' => $users_temp
                    ])
                ]);
            }

            return response()->json([
                'status' => false,
                'data' => collect([
                    'message' => 'Нет пользователей для отправки уведомлений',
                ])
            ]);

        } catch (ValidationException $e) {
            Logger::debug($e->getMessage());

            return $this->sendError(self::validatorMessages(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(path="/notifications/events/",tags={"External Notifications (для сторонних систем)"},
     *     summary="Возвращает доступные событие по каналу.",security={{"api_key": {}}},
     * @OA\Response(response=200,description="OK", @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean"),
     *      @OA\Property(property="data", type="object",
     *      @OA\Property(property="message", type="string", description="Сообщение от сервера"),
     *      @OA\Property(property="events", type="array", @OA\Items(), description="События"),
     * ))),@OA\Response(response=400,description="Bad request"),@OA\Response(response=500,description="Error server"))
     * @param Request $request
     * @return array
     */
    public function events(Request $request)
    {
        if (is_null(Auth()->user())) {
            return $this->sendError("You do not have the required authorization", Response::HTTP_NOT_FOUND);
        }

        $events = Cache::get('cache_events_channel_' . Auth()->user()->id);

        if (is_null($events)) {
            $events = Auth()->user()->events;

            if ($events instanceof Collection) {
                if ($events->count() === 0) {
                    return $this->sendError("Not found events", Response::HTTP_NOT_FOUND);
                }

                $events = $events->map(function ($e) {
                    return collect([
                        'id' => $e->event->id,
                        'name' => $e->event->name,
                    ]);
                });

                Cache::add('cache_events_channel_' . Auth()->user()->id, $events, 10);
            }
        }

        return $this->sendResponse([
            'message' => 'Events for your channel successfully found',
            'events' => $events
        ], $request);
    }
}
