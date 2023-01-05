<?php

namespace App\Http\Controllers\API\External\User;

use App\Http\Controllers\API\ApiController;
use App\Models\Channels\ModelChannelsUsers;
use App\Models\Notifications\ModelNotificationsList;
use App\Traits\TraitValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApiControllerExternalUserChannel
 *
 * @OA\Tag(
 *      name="External User",
 *      description="Внешний компонент для работы с пользователями"
 *  )
 *
 * @package App\Http\Controllers\API\External\User
 */
class ApiControllerExternalUserChannels extends ApiController
{
    use TraitValidator;

    /**
     * @OA\Get(path="/v1/user/channels",tags={"External User"},summary="Возвращает каналы доступные пользователю.",security={{"api_key": {}}},
     * @OA\Response(response=200,description="OK",
     *     @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean"),
     *      @OA\Property(property="data", type="object",
     *      @OA\Property(property="message", type="string"),
     *      @OA\Property(property="channels", type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer", description="ID канала"),
     *              @OA\Property(property="name", type="string", description="Имя канала"),
     *              @OA\Property(property="events", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", description="ID события"),
     *                      @OA\Property(property="name", type="string", description="Имя события"),
     *                  ), description="Событие для канала"),
     *              ), description="Список каналов и событий по каналам"),
     * ))),@OA\Response(response=404,description="Channel not found"),@OA\Response(response=500,description="Error server"))
     *
     * Возвращаем каналы пользователя
     * @param Request $request
     * @return array|JsonResponse
     */
    public function index(Request $request)
    {
        $cache = true;
        $channels = null; // todo доделать кеширование каналов

        if (is_null($channels)) {

            $cache = false;

            $channels = ModelChannelsUsers::whereUserId(Auth()->user()->originalUser()->id)
                ->with(['channel'])->get();

            if ($channels->count() === 0) {
                return $this->sendError('Нет доступных каналов для пользователя', Response::HTTP_NOT_FOUND);
            }

            $channels = $channels->filter(function ($e){
                if (!is_null($e->channel) && !is_null($e->channel->events)) {
                    if(ModelNotificationsList::whereChannelId($e->channel->id)->exists()){
                        return true;
                    }
                }

                return false;
            })->map(function ($e) {
                $events = $e->channel->events->map(function ($e) {
                    return collect([
                        'id' => $e->event->id,
                        'name' => $e->event->name
                    ]);
                });

                return collect([
                    'id' => $e->channel->id,
                    'name' => $e->channel->name,
                    'events' => $events
                ]);
            })->values();

            if ($channels->count() === 0) {
                return response()->json([
                    'status' => false,
                    'data' => [
                        'message' => 'Нет доступных каналов для пользователя',
                        'channels' => [],
                        'cache' => false
                    ]
                ], Response::HTTP_NOT_FOUND);
            }
        }

        return $this->sendResponse([
            'message' => 'Channels successfully found',
            'channels' => $channels,
            'cache' => $cache
        ], $request);
    }
}
