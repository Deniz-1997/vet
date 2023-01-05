<?php

namespace App\Http\Controllers\API\Internal\Channels;

use App\Http\Controllers\API\ApiController;
use App\Models\Channels\ModelChannels;
use App\Modules\Channels\ChannelsListModule;

/**
 * Class ApiControllerInternalChannelsList
 *
 *
 * @package App\Http\Controllers\API\Internal\Channels
 */

/**
 * @OA\Tag(
 *      name="Internal Channels List",
 *      description="Внутренний компонент для работы с каналами"
 *  )
 *
 * @OA\Get(
 *     path="/v2/channels/list",
 *     tags={"Internal Channels List"},
 *     summary="Возвращает события для каналов с пагинаций.",
 *     security={{"api_key": {}}},
 *     @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="current_page", type="integer", example=1, description="Текущая страница"),
 *              @OA\Property(property="data", type="array", description="Массив с элементами", @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1, description="ID записи"),
 *                  @OA\Property(property="sms_limit", type="integer", example=1, description="Лимит по смс"),
 *                  @OA\Property(property="email_limit", type="integer", example=1, description="Лимит по письмам"),
 *                  @OA\Property(property="send_sms", type="boolean", example=1, description="Отправка по смс каналу"),
 *                  @OA\Property(property="send_email", type="boolean", example=1, description="Отправка по каналу почты "),
 *                  @OA\Property(property="name", type="string", example="Test", description="Название канала"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
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
 * @OA\Post(
 *     path="/v2/channels/list",
 *     tags={"Internal Channels List"},
 *     summary="Создание канала.",
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(property="sms_limit", type="integer", example=1, description="Лимит в день отправка sms"),
 *                  @OA\Property(property="email_limit", type="integer", example=10, description="Лимит в день отправка писем"),
 *                  @OA\Property(property="send_sms", type="boolean", example="true", description="Отправка по смс"),
 *                  @OA\Property(property="send_email", type="boolean", example="true", description="Отправка по почте"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя канала"),
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
 *                  @OA\Property(property="sms_limit", type="integer", example=1, description="Лимит по смс"),
 *                  @OA\Property(property="email_limit", type="integer", example=1, description="Лимит по письмам"),
 *                  @OA\Property(property="send_sms", type="boolean", example=1, description="Отправка по смс каналу"),
 *                  @OA\Property(property="send_email", type="boolean", example=1, description="Отправка по каналу почты "),
 *                  @OA\Property(property="name", type="string", example="Test", description="Название канала"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Put(
 *     path="/v2/channels/list/{id}",
 *     tags={"Internal Channels List"},
 *     summary="Обновляем запись об канале.",
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
 *                  @OA\Property(property="sms_limit", type="integer", example=1, description="Лимит в день отправка sms"),
 *                  @OA\Property(property="email_limit", type="integer", example=10, description="Лимит в день отправка писем"),
 *                  @OA\Property(property="send_sms", type="boolean", example="true", description="Отправка по смс"),
 *                  @OA\Property(property="send_email", type="boolean", example="true", description="Отправка по почте"),
 *                  @OA\Property(property="name", type="string", example="Test", description="Имя канала"),
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
 *                  @OA\Property(property="sms_limit", type="integer", example=1, description="Лимит по смс"),
 *                  @OA\Property(property="email_limit", type="integer", example=1, description="Лимит по письмам"),
 *                  @OA\Property(property="send_sms", type="boolean", example=1, description="Отправка по смс каналу"),
 *                  @OA\Property(property="send_email", type="boolean", example=1, description="Отправка по каналу почты "),
 *                  @OA\Property(property="name", type="string", example="Test", description="Название канала"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *              )),
 *          )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error server",
 *     )
 * )
 * @OA\Get(
 *     path="/v2/channels/list/{id}",
 *     tags={"Internal Channels List"},
 *     summary="Возвращает информацию по каналу.",
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
 *                  @OA\Property(property="sms_limit", type="integer", example=1, description="Лимит по смс"),
 *                  @OA\Property(property="email_limit", type="integer", example=1, description="Лимит по письмам"),
 *                  @OA\Property(property="send_sms", type="boolean", example=1, description="Отправка по смс каналу"),
 *                  @OA\Property(property="send_email", type="boolean", example=1, description="Отправка по каналу почты "),
 *                  @OA\Property(property="name", type="string", example="Test", description="Название канала"),
 *                  @OA\Property(property="created_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было отправлено оповещение"),
 *                  @OA\Property(property="updated_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
 *                  @OA\Property(property="deleted_at", example="2020-01-01 19:00:00", type="string", description="Дата, когда было создано оповещение"),
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
 *     path="/v2/channels/list/{id}",
 *     tags={"Internal Channels List"},
 *     summary="Удаляем запись по ID.",
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
class ApiControllerInternalChannelsList extends ApiController
{
    /**
     * ApiControllerInternalChannelsList constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setModuleResources(ChannelsListModule::setModel(ModelChannels::query()))
            ->setRoles(['administrator', 'subadministrator']);
    }
}
