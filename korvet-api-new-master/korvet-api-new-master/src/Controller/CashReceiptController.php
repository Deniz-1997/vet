<?php

namespace App\Controller;

use App\Entity\Cash\CashReceipt;
use App\Repository\Cash\CashReceiptRepository;
use DateTime;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * Class CashReceiptController
 * @Route("/api/cash-receipt")
 * @Resource(
 *     description="Кассовый чек",
 *     tags={"CashReceipt"},
 *     summariesMap={
 *          "list": "Получить список кассовых чеков",
 *          "get": "Получить кассовый чек",
 *          "post": "Создать кассовый чек",
 *          "delete": "Удалить кассовый чек",
 *          "patch": "Обновить кассовый чек"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список кассовых чеков",
 *          "get": "Возвращает кассовый чек по идентификатору",
 *          "post": "Создает новую кассовый чек",
 *          "delete": "Удаляет существующий кассовый чек",
 *          "patch": "Обновляет существующий кассовый чек"
 *     }
 * )
 */
class CashReceiptController extends AbstractController
{
    public const ENTITY_CLASS = CashReceipt::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @Route("/{id}/return/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"POST"})
     * @SWG\Post(
     *     summary="Создать чек возврата",
     *     description="Создает новый чек возврата, на основании существующего ранее чека. Имеет смысл только для типов чеков SELL и BUY, иначе выдает ошибку. В случае успеха функция выдает id созданного чека возврата.",
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
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     * @param CashReceiptRepository $cashReceiptRepository
     * @param EntityManagerInterface $entityManager
     * @param BaseResponse $response
     * @return Response
     * @throws ApiException
     * @throws DBALException
     */
    public function getOpenedShiftDocumentsAction($id, CashReceiptRepository $cashReceiptRepository, EntityManagerInterface $entityManager, BaseResponse $response)
    {
        // TODO: https://portal.web-slon.ru/company/personal/user/1/tasks/task/view/9524/
        //Создает новый чек возврата, на основании существующего ранее чека. Имеет смысл только для типов чеков SELL и BUY, иначе выдает ошибку.
        //
        //В ответе функция выдает id созданного чека возврата.

        $cashReceipt = $cashReceiptRepository->findCashReceipt($id);
        if (!$cashReceipt) {
            throw new ApiException('cashier.cash_receipt.not_found', 'CASH_RECEIPT_NOT_FOUND', null, 404);
        }

        if (!in_array($cashReceipt->getType()->code, [CashReceiptTypeEnum::BUY, CashReceiptTypeEnum::SELL])) {
            throw new ApiException('cashier.cash_receipt.return_wrong_type', 'CASH_RECEIPT_RETURN_WRONG_TYPE', null, 400);
        }

        if (!$cashReceiptReturn = $cashReceiptRepository->findOneBy(['baseDocument' => $cashReceipt])) {
            $cashReceiptReturn = clone $cashReceipt;
            $newFiscal = new FiscalParameters();
            $newFiscal->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));
            $cashReceiptReturn->setFiscal($newFiscal);
            $cashReceiptReturn->setCreatedAt(new DateTime());
            $cashReceiptReturn->setType(CashReceiptTypeEnum::getItem(CashReceiptTypeEnum::SELL_RETURN));
            $cashReceiptReturn->setBaseDocument($cashReceipt);

            $items = [];
            foreach ($cashReceipt->getItems() as $item) {
                $items[] = clone $item;
            }
            $cashReceiptReturn->setItems($items);

            $entityManager->persist($cashReceiptReturn);
            $entityManager->flush();
        }

        return $response->setResponse(['id' => $cashReceiptReturn->getId()])->send();
    }
}
