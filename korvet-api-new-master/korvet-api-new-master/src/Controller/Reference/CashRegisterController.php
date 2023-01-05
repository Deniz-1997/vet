<?php

namespace App\Controller\Reference;

use App\Repository\Cash\CashFlowRepository;
use App\Repository\Cash\CashReceiptRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Packages\DBAL\Types\ShiftStateEnum;
use App\Entity\Reference\CashRegister;
use App\Entity\Shift;
use App\Repository\Reference\CashRegisterRepository;
use App\Repository\ShiftRepository;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * Class CashRegisterController
 * @Route("/api/reference/cash-register")
 * @Resource(
 *     description="ККМ",
 *     tags={"CashRegister"},
 *     summariesMap={
 *          "list": "Получить список ККМ",
 *          "get": "Получить ККМ",
 *          "post": "Создать ККМ",
 *          "delete": "Удалить ККМ",
 *          "patch": "Обновить ККМ"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список ККМ",
 *          "get": "Возвращает ККМ по идентификатору",
 *          "post": "Создает новую ККМ",
 *          "delete": "Удаляет существующую ККМ",
 *          "patch": "Обновляет существующую ККМ"
 *     }
 * )
 */
class CashRegisterController extends AbstractController
{
    public const ENTITY_CLASS = CashRegister::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @Route("/{id}/shift/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"GET"})
     * @SWG\Get(
     *     summary="Получить текущую открытую смену",
     *     description="Возвращает сущность CashRegister или null, если смена не открыта.",
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
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(ref="#/definitions/ShiftGetResponse")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     * @param string $id
     * @param BaseResponse $response
     * @param ShiftRepository $shiftRepository
     * @param CashRegisterRepository $cashRegisterRepository
     * @return Response
     * @throws ApiException
     */
    public function getOpenedShiftAction(string $id, BaseResponse $response, ShiftRepository $shiftRepository, CashRegisterRepository $cashRegisterRepository)
    {
        //https://portal.web-slon.ru/company/personal/user/1/tasks/task/view/9518/
        //Ищем текущую открытую смену.
        //Возвращает сущность CashRegister или null, если смена не открыта.

        $cashRegister = $cashRegisterRepository->findCashRegister($id);
        if (!$cashRegister) {
            throw new ApiException('cashier.cash_register.not_found', 'CASH_REGISTER_NOT_FOUND', null, 404);
        }

        $openedShift = $shiftRepository->findLastShift($cashRegister);

        return $response->setResponse($openedShift)->setSerializationContext([
            'groups' => ['default']
        ])->send();
    }

    /**
     * @Route("/{id}/shift/documents/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"GET"})
     * @SWG\Get(
     *     summary="Получить журнал кассовых документов",
     *     description="Возвращает единый список документов “Кассовый чек” и “Внесение / выплата” для текущей открытой кассовой смены.",
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
     *         description="Успешный ответ сервиса",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     * @param string $id
     * @param CashRegisterRepository $cashRegisterRepository
     * @param ShiftRepository $shiftRepository
     * @param CashFlowRepository $cashFlowRepository
     * @param CashReceiptRepository $cashReceiptRepository
     * @param BaseResponse $response
     * @return Response
     * @throws ApiException
     * @throws \Doctrine\DBAL\Exception
     */
    public function getOpenedShiftDocumentsAction($id, CashRegisterRepository $cashRegisterRepository, ShiftRepository $shiftRepository, CashFlowRepository $cashFlowRepository, CashReceiptRepository $cashReceiptRepository, BaseResponse $response)
    {
        // TODO: https://portal.web-slon.ru/company/personal/user/1/tasks/task/view/9523/
        //Возвращает единый список документов “Кассовый чек” и “Внесение / выплата” для текущей открытой кассовой смены.

        $cashRegister = $cashRegisterRepository->findCashRegister($id);
        if (!$cashRegister) {
            throw new ApiException('cashier.cash_register.not_found', 'CASH_REGISTER_NOT_FOUND', null, 404);
        }

        $openedShift = $shiftRepository->findLastShift($cashRegister);
        if (!$openedShift || $openedShift->getState()->code !== ShiftStateEnum::OPEN) {
            throw new ApiException('cashier.shift.not_found_opened_shift', 'CASH_REGISTER_NOT_FOUND_OPENED_SHIFT', null, 404);
        }

        $cashFlows = $cashFlowRepository->findByShift($openedShift);
        $cashReceipts = $cashReceiptRepository->findByShift($openedShift);

        return $response->setResponse([
            'cashFlows' => $cashFlows,
            'cashReceipts' => $cashReceipts,
        ])->setSerializationContext([
            'groups' => ['default']
        ])->send();
    }
}
