<?php

namespace App\Http\Controllers\API\External\User;

use App\Http\Controllers\API\ApiController;
use App\Models\Templates\ModelTemplatesGroupUser;
use App\Models\Templates\ModelTemplatesList;
use App\Models\User\ModelUserGroups;
use App\Traits\TraitValidator;
use Cache;
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
class ApiControllerExternalUserTemplate extends ApiController
{
    use TraitValidator;

    /**
     * @OA\Get(path="/v1/user/template",tags={"External User"},summary="Возвращает шаблоны доступные пользователю.",security={{"api_key": {}}},
     * @OA\Response(response=200,description="OK", @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean"),
     *      @OA\Property(property="data", type="object",
     *      @OA\Property(property="message", type="string"),
     *      @OA\Property(property="template", type="array", @OA\Items(
     *              @OA\Property(property="color", type="string", description="Цвет текста"),
     *              @OA\Property(property="format_date", type="string", description="Формат даты и время"),
     *              @OA\Property(property="show_status_notify", type="boolean", description="Показывать-ли статус"),
     *              @OA\Property(property="show_date", type="boolean", description="Показывать-ли дату"),
     *              @OA\Property(property="show_time", type="boolean", description="Показывать-ли время"),
     *     ), description="Список шаблонов"),
     * ))),
     *     @OA\Response(response=404,description="Not found group or Not found template"),
     *     @OA\Response(response=500,description="Error server")
     * )
     *
     * Возвращаем каналы пользователя
     * @param Request $request
     * @return array|JsonResponse
     */
    public function index(Request $request)
    {
        $cache = true;
        $array = Cache::get('cache_user_template_' . Auth()->user()->originalUser()->id);

        if (is_null($array)) {

            $cache = false;

            $userGroups = ModelUserGroups::whereUserId(Auth()->user()->originalUser()->id)->get();

            if ($userGroups->count() === 0) {
                return $this->sendError('Not found group', Response::HTTP_NOT_FOUND);
            }

            $templateGroup = ModelTemplatesGroupUser::select()->whereIn('group_id', $userGroups->map(function ($e) {
                return $e->group_id;
            }))->orderBy('priority', 'DESC')->first();

            if (is_null($templateGroup)) {
                return response()->json([
                    'status' => false,
                    'data' => [
                        'message' => 'Шаблоны не найдены',
                        'template' => [],
                        'cache' => false
                    ]
                ], Response::HTTP_NOT_FOUND);
            }

            $array = ModelTemplatesList::select(['color', 'format_date', 'show_status_notify', 'show_date', 'show_time'])->whereId($templateGroup->template_id)->first();

            Cache::add('cache_user_template_' . Auth()->user()->originalUser()->id, $array, 60);
        }

        return $this->sendResponse([
            'message' => 'Template successfully found',
            'template' => $array,
            'cache' => $cache
        ], $request);
    }
}
