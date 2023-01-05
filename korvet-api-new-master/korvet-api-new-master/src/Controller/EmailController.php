<?php

namespace App\Controller;

use App\Entity\Email;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\Annotation\Resource;
use App\Service\CRUD\AddItemService;
use App\Controller\CRUD\{
    GetItemTrait, GetListTrait, UpdateItemTrait, DeleteItemTrait
};
use App\Exception\ApiException;

/**
 * @Route("/api/email")
 * @Resource(
 *     description="Main desc",
 *     tags={"Email"},
 *     summariesMap={
 *          "list": "Получить список email",
 *          "get": "Получить email",
 *          "post": "Создать email",
 *          "delete": "Удалить email",
 *          "put": "Обновить email"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список email",
 *          "get": "Возвращает email по идентификатору",
 *          "post": "Создает новый email",
 *          "delete": "Удаляет существующий email",
 *          "put": "Обновляет существующий email"
 *     }
 * )
 */
class EmailController extends AbstractController
{
    public const ENTITY_CLASS = Email::class;

    /**
     * @var AddItemService
     */
    private AddItemService $service;

    use GetItemTrait, GetListTrait, UpdateItemTrait, DeleteItemTrait;

    public function __construct(AddItemService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/", methods={"POST"})
     * @SWG\Post(
     *     summary="Добавить email-сообщение",
     *     description="Добавляет email-сообщение в очередь на отправку.",
     *     @SWG\Parameter(
     *          parameter="body",
     *          name="body",
     *          in="body",
     *          @SWG\Schema(ref=@Model(type=AddEmailRequest::class))
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные добавленного письма",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные добавленного письма",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *
     *  examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": {"id": 1},
     *                  "errors": null
     *              }
     *         },examples={
     *              "application/json":{
     *              "status": true,
     *              "errors": null,
     *              "requestId": null,
     *              "response":{
     *                  "emailFrom": "user@example.com",
     *                  "emailTo": "user@example.com",
     *                  "subject": "string",
     *                  "cc": null,
     *                  "bcc": null,
     *                  "replyTo": null,
     *                  "parameters": null,
     *                  "template": null,
     *                  "theme":{
     *                      "isDefault": true,
     *                      "id": 1,
     *                      "name": "Тема по умолчанию",
     *                      "file": "default.html.twig"
     *                  },
     *                  "body": "string",
     *                  "application": "none",
     *                  "status": 1,
     *                  "createdAt": "19.02.2019 20:12:51",
     *                  "sentAt": null,
     *                  "response": null,
     *                  "id": 9
     *                  }
     *              }
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     *         },
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addAction(Request $request): Response
    {
        return $this->service->add($request->getContent(), self::ENTITY_CLASS,
            ['addEmail', 'Default'])->send();
    }

    /**
     * @Route("/add-by-template/", methods={"POST"})
     * @SWG\Post(
     *     summary="Добавить email-сообщение по шаблону",
     *     description="Добавляет email-сообщение в очередь на отправку. Для
     *     создания сообщения используется шаблон.",
     *     @SWG\Parameter(
     *          parameter="body",
     *          name="body",
     *          in="body",
     *          @SWG\Schema(ref=@Model(type=AddEmailByTemplateRequest::class))
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные добавленного письма",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *  examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": {"id": 1},
     *                  "errors": null
     *              }
     *         },
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addByTemplateAction(Request $request): Response
    {
        return $this->service->add($request->getContent(), self::ENTITY_CLASS,
            ['addEmailByTemplate', 'Default'])->send();
    }
}
