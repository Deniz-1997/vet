<?php

namespace App\Http\Controllers\API\External\Event;

use App\Http\Controllers\API\ApiController;
use App\Models\Events\ModelEventsList;
use App\Traits\TraitValidator;
use Illuminate\Http\Request;

/**
 * Class ApiControllerExternalEventIcons
 *
 * @OA\Tag(
 *      name="External events",
 *      description="Внешний компонент для работы с событиями"
 *  )
 *
 * @package App\Http\Controllers\API\External\Event
 */
class ApiControllerExternalEventIcons extends ApiController
{
    use TraitValidator;

    /**
     * @OA\Get(path="/v1/events/icons",tags={"External events"},summary="Возвращает икогки для событий.",security={{"api_key": {}}},
     * @OA\Response(response=200,description="OK",
     *     @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean"),
     *      @OA\Property(property="data", type="object",
     *      @OA\Items(
     *              @OA\Property(property="id", type="integer", description="ID канала"),
     *              @OA\Property(property="url", type="string", description="Ссылка на иконку события"),
     *      )
     *      )),@OA\Response(response=404,description="Icons not found"),@OA\Response(response=500,description="Error server"))
     *)
     * Возвращаем каналы пользователя
     * @param Request $request
     * @return array|JsonResponse
     */
    public function get(Request $request)
    {
        $data = ModelEventsList::with(['file'])->get()->map(function ($e) {
            if (is_null($e->file)) return [];
            return ['id' => $e->id, 'url' => $e->file->path];
        })->filter(function ($e) {
            return count($e);
        });

        $data->push([
            "id"    =>  0,
            "url"   =>  env('APP_URL').'/img/logo.png'
        ]);

        return $this->sendResponse($data->values()->toArray(), $request);
    }
}
