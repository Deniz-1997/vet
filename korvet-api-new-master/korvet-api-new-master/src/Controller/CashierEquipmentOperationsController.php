<?php

namespace App\Controller;

use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Response\Async\AsyncResponse;
use App\Packages\Response\Async\AsyncResponseBody;
use App\Service\CashierEquipmentMessageService;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * Class CashRegisterOperationsController
 * @Route("/api")
 */
class CashierEquipmentOperationsController extends AbstractController
{
    /** @var CashierEquipmentMessageService */
    private $cashEquipmentMessage;

    /**
     * CashierEquipmentOperationsController constructor.
     * @param CashierEquipmentMessageService $cashEquipmentMessageService
     */
    public function __construct(CashierEquipmentMessageService $cashEquipmentMessageService)
    {
        $this->cashEquipmentMessage = $cashEquipmentMessageService;
    }

    /**
     * @Route("/reference/cash-register/{id}/shift/open/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Открыть кассовую смену",
     *     description="Отправляет асинхронную команду на ККМ по открытию кассовой смены. Ответ можно получить по идентификатору запроса (correlationId), который возвращает данная функция.",
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор ККМ",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     *
     * @param AsyncResponse $response
     * @return Response
     * @throws ApiException
     */
    public function openShiftAction(
        string $id,
        AsyncResponse $response
    ): Response {
        $requestId = $this->cashEquipmentMessage->openShift($id);

        return $response->setResponse(
            (new AsyncResponseBody())->setCorrelationId($requestId)
        )->send();
    }

    /**
     * @Route("/reference/cash-register/{id}/shift/x-report/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Снять отчёт без гашения",
     *     description="Отправляет асинхронную команду на ККМ по снятию отчета без гашения. Обратно ответ не приходит.",
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор ККМ",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     *
     * @param BaseResponse $response
     * @return Response
     * @throws ApiException
     */
    public function xReportAction(string $id, BaseResponse $response): Response
    {
        $this->cashEquipmentMessage->reportX($id);
        return $response->send();
    }

    /**
     * @Route("/reference/cash-register/{id}/shift/close/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Закрыть кассовую смену",
     *     description="Отправляет асинхронную команду на ККМ по закрытию кассовой смены. Ответ можно получить по идентификатору запроса (correlationId), который возвращает данная функция.",
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор ККМ",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     *
     * @param AsyncResponse $response
     * @return Response
     * @throws ApiException
     */
    public function closeShiftAction(string $id, AsyncResponse $response): Response
    {
        $requestId = $this->cashEquipmentMessage->closeShift($id);

        return $response->setResponse(
            (new AsyncResponseBody())->setCorrelationId($requestId)
        )->send();
    }

    /**
     * @Route("/reference/cash-register/{id}/continue-print/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Допечатать документ",
     *     description="Отправляет асинхронную команду на ККМ по допечатыванию последнего документа. Обратно ответ не приходит.",
     *     tags={"CashRegister"},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор ККМ",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     *
     * @param BaseResponse $response
     * @return Response
     * @throws ApiException
     */
    public function continuePrintAction(string $id, BaseResponse $response): Response
    {
        $this->cashEquipmentMessage->continuePrint($id);
        return $response->send();
    }

    /**
     * @Route("/reference/cash-register/{id}/info/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Запрос параметров регистрации ККМ",
     *     description="Отправляет асинхронную команду на ККМ по получению параметров регистрации ККМ. Ответ можно получить по идентификатору запроса (correlationId), который возвращает данная функция.",
     *     tags={"CashRegister"},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор ККМ",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     *
     * @param AsyncResponse $response
     * @return Response
     * @throws ApiException
     */
    public function cashRegisterInfoAction(string $id, AsyncResponse $response): Response
    {
        //Отправляем сообщение в RabbitMQ в очередь cashRegisterServer.{id}, операция Запрос параметров регистрации ККМ.
        //Возвращаем в ответ на запрос сообщение, что запрос получен (не оставляем висеть соединение).
        //Слушаем RabbitMQ, при появлении сообщения с ответом, отправляем на фронт ответ.

        $requestId = $this->cashEquipmentMessage->getRegistrationInfo($id);

        return $response->setResponse(
            (new AsyncResponseBody())->setCorrelationId($requestId)
        )->send();
    }

    /**
     * @Route("/reference/cash-register/{id}/status/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Отчет о текущем состоянии расчетов",
     *     description="Отправляет асинхронную команду на ККМ по получению отчета о текущем состоянии расчетов. Ответ можно получить по идентификатору запроса (correlationId), который возвращает данная функция.",
     *     tags={"CashRegister"},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор ККМ",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     *
     * @param AsyncResponse $response
     * @return Response
     * @throws ApiException
     */
    public function cashRegisterStatusAction(string $id, AsyncResponse $response): Response
    {
        //Получаем данные текущего пользователя: ФИО и ИНН.
        //Отправляем сообщение в RabbitMQ в очередь cashRegisterServer.{id}, операция Отчет о текущем состоянии расчетов.
        //Возвращаем клиенту в ответ на запрос сообщение, что запрос получен (не оставляем висеть соединение).
        //Слушаем RabbitMQ, при появлении сообщения с ответом, отправляем на фронт ответ.

        $requestId = $this->cashEquipmentMessage->reportOfExchangeStatus($id);

        return $response->setResponse(
            (new AsyncResponseBody())->setCorrelationId($requestId)
        )->send();

    }

    /**
     * @Route("/cash-receipt/{id}/register/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Пробить кассовый чек",
     *     description="Отправляет асинхронную команду на ККМ по пробитию кассового чека. Ответ можно получить по идентификатору запроса (correlationId), который возвращает данная функция.",
     *     tags={"CashReceipt"},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор чека ККМ",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param int $id
     *
     * @param AsyncResponse $response
     * @return Response
     * @throws Exception
     */
    public function registerCashReceiptAction(int $id, AsyncResponse $response): Response
    {
        $requestId = $this->cashEquipmentMessage->registerCashReceipt($id);

        return $response->setResponse(
            (new AsyncResponseBody())->setCorrelationId($requestId)
        )->send();
    }

    /**
     * @Route("/cash-flow/{id}/register/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Пробить чек внесения / выплаты",
     *     description="Отправляет асинхронную команду на ККМ по пробитию чека внесения / выплаты. Ответ можно получить по идентификатору запроса (correlationId), который возвращает данная функция.",
     *     tags={"CashFlow"},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор чека ККМ внесения / выплаты",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Команда отправлена",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     * @param AsyncResponse $response
     *
     * @return Response
     *
     * @throws ApiException
     */
    public function registerCashFlowAction(string $id, AsyncResponse $response): Response
    {
        //Получаем данные текущего пользователя: ФИО и ИНН.
        //По id получаем данные Чека внесения / выдачи, а также данные ККМ-сервера из ККМ в чеке.
        //Отправляем сообщение в RabbitMQ в очередь cashRegisterServer.{id}, операция Пробить чек внесения / выплаты.
        //Возвращаем в ответ на запрос сообщение, что запрос получен (не оставляем висеть соединение).
        //Слушаем RabbitMQ, при появлении сообщения о том, что чек пробит, обновляем документ Чек внесения / выдачи. Сохраняется признак, что чек пробит на ККМ и фискальные параметры чека, после чего любые изменения документа становятся невозможны.
        //Извещаем фронт об успешном пробитии чека. Либо если возникли ошибки, тогда сообщаем об этом пользователю.
        //В ответе от ККМ может прийти warnings.notPrinted = true, в этом случае сообщаем пользователю: “Документ закрыт, но не допечатан. Вероятно произошел сбой печати (самый стандартный случай - закончилась бумага). Необходимо устранить неисправность. После устранения неисправности требуется продолжить печать.”

        $requestId = $this->cashEquipmentMessage->registerCashFlow($id);

        return $response->setResponse(
            (new AsyncResponseBody())->setCorrelationId($requestId)
        )->send();
    }
}
