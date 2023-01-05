<?php

namespace App\Http\Controllers\API\External\User;

use App\Http\Controllers\API\ApiController;
use App\Models\Channels\ModelChannelsNotificationsCount;
use App\Models\Channels\ModelChannelsUsers;
use App\Models\Notifications\ModelNotificationsEvents;
use App\Models\Notifications\ModelNotificationsSend;
use App\Modules\Notifications\NotificationsSendsModule;
use App\Traits\TraitValidator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApiControllerExternalUserEvents
 *
 * @OA\Tag(
 *      name="External User",
 *      description="Внешний компонент для работы с пользователями"
 *  )
 *
 * @package App\Http\Controllers\API\External\User
 */
class ApiControllerExternalUserEvents extends ApiController
{
    use TraitValidator;

    /**
     * @OA\Get(path="/v1/user/events",tags={"External User"},summary="Возвращает события канала доступные пользователю.",security={{"api_key": {}}},
     *  @OA\Response(response=200,description="OK",
     *      @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean"),
     *              @OA\Property(property="data", type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="events", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", description="ID события"),
     *                      @OA\Property(property="name", type="string", description="Имя события"),
     *                      @OA\Property(property="status", type="string", description="Статус события"),
     *                      @OA\Property(property="created_at", type="string", description="Когда было создано"),
     *                      @OA\Property(property="updated_at", type="string", description="Последнее обновление события"),
     *                      @OA\Property(property="channel_id", type="integer", description="ID канала"),
     *                      @OA\Property(property="unread_count", type="integer", description="Количество не просмотренных уведомлений по событию"),
     *                      @OA\Property(property="icon", type="string", description="Иконка события"),
     *                      @OA\Property(property="img_icon", type="string", description="Изображения события"),
     *                  ), description="Список событий")
     *              )
     *      )
     *  ),
     *
     *     @OA\Response(response=404,description="Channel or events not found"),@OA\Response(response=500,description="Error server"))
     *
     * @param Request $request
     * @return array|ResponseFactory|Model|Builder|JsonResponse|Response
     */
    public function index(Request $request)
    {
        $channel_id = $request->get('channel_id');
        $limit = $request->get('limit');
        $offset = $request->get('offset') === null ? 0 : $request->get('offset');
        $total = 0;

        $name_cache = 'cache_user_events_' . Auth()->user()->originalUser()->id;

        if (!is_null($channel_id)) {
            $name_cache .= "_$channel_id";
        }

        $events = collect();

        if ($events->count() === 0) {
            $cache = false;

            $user_id = Auth()->user()->originalUser()->id;

            $channels = ModelChannelsUsers::whereUserId($user_id)->with(['channel'])
                ->whereHas('channel', function ($query) use ($channel_id) {
                    if (!is_null($channel_id)) {
                        $query->where('id', '=', $channel_id);
                    }
                })
                ->get();

            if ($channels->count() === 0) {
                return response()->json([
                    'status' => false,
                    'data' => [
                        'message' => 'Канал недоступен',
                        'events' => [],
                        'cache' => false
                    ]
                ], Response::HTTP_NOT_FOUND);
            }

            $channels->map(function ($userChannel) use (&$events, $user_id) {
                if (!is_null($userChannel->channel)
                    && (!is_null($userChannel->channel->notificationsList)
                        && count($userChannel->channel->notificationsList) > 0)) {

                    $userChannel
                        ->channel
                        ->notificationsList
                        ->map(function ($channelList) use (&$events, $userChannel, $user_id) {

////                            $eventsModel = \Cache::get('events_model1_' . $channelList->id);
//                            $eventsModel = null;
//
//                            if (is_null($eventsModel)) {
//
//                                if (!is_null($eventsModel)) {
//                                    \Cache::add('events_model1_' . $channelList->id, $eventsModel, 120);
//                                }
//                            }

                            $eventsModel = ModelNotificationsEvents::whereNotificationsId($channelList->id)->with(['event'])->get();

                            # Лучкин
                            if ($user_id === 75) {
//                                dd($eventsModel->first()->event);
                            }

                            if ($eventsModel->count() > 0 &&
                                ModelNotificationsSend::whereIn('notify_event_id', $eventsModel->map(function ($e) {
                                    return $e->id;
                                })->toArray())->whereUserId($user_id)->exists()
                            ) {
                                $counter = ModelChannelsNotificationsCount::whereNotificationId($channelList->id)->whereUserId($user_id)->select('count')->first();

                                $events->push([
                                    'id' => $channelList->id,
                                    'name' => $channelList->name,
                                    'user_id' => $channelList->status,
                                    'status' => $channelList->status,
                                    'created_at' => $channelList->created_at->format('Y-m-d H:i:s'),
                                    'updated_at' => $channelList->updated_at->format('Y-m-d H:i:s'),
                                    'channel_id' => $userChannel->channel_id,
                                    'unread_count' => is_null($counter) ? 0 : $counter->count,
                                    'icon' => $channelList->icon,
                                    'event_id' => is_null($eventsModel->first()) ? 0 : $eventsModel->first()->event->id,
                                    'img_icon' => is_null($eventsModel->first()->event->file) ? env('APP_URL').'/img/logo.png' : $eventsModel->first()->event->file->path
                                ]);
                            }
                        });
                }
            });

            if ($events->count() === 0) {
                return response()->json([
                    'status' => false,
                    'data' => [
                        'message' => 'События не найдены',
                        'total' => 0,
                        'events' => [],
                        'cache' => false
                    ]
                ], Response::HTTP_NOT_FOUND);
            }
            $total = $events->count();
            if ($limit !== null) {
                $events = $events->sortByDesc('updated_at')->skip($offset)->take($limit)->values()->all();
            }
            else {
                $events = $events->sortByDesc('updated_at')->values()->all();
            }

//            Cache::add($name_cache, collect($events), 5);
        }
        
        return $this->sendResponse([
            'message' => 'Events successfully found',
            'total' => $total,
            'events' => $events,
            'cache' => $cache
        ], $request);
    }
}
