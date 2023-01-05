<?php

namespace App\Controller\Shop;

use App\Controller\Documents\DocumentControllerTrait;
use App\Entity\Cash\CashReceipt;
use App\Entity\Document\DocumentHistory;
use App\Entity\ProductStock;
use App\Entity\Reference\Stock;
use App\Enum\DocumentStateEnum;
use App\Interfaces\ServiceDocumentInterface;
use App\Repository\Shop\ShopOrderRepository;
use App\Repository\Cash\CashierScheduleRepository;
use App\Service\Document\DocumentService;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use InvalidArgumentException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Psr\Log\LoggerInterface;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Operation;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Interfaces\ApiControllerInterface;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\DynamicEntityClassControllerInterface;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Service\CRUD\UpdateItemService;
use App\Service\DependenciesService;
use App\Service\SerializationContextFetcher;
use App\Service\ValidationService;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Packages\DBAL\Types\PaymentMethodEnum;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Packages\DBAL\Types\PaymentStateEnum;
use App\Packages\DBAL\Types\ReceiptDeliveryTypeEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\ReceiptItem;
use App\Entity\Shop\ShopOrder;
use App\Repository\Reference\CashRegisterRepository;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Service\HandlerException\Validation\ValidationException;
use App\Service\DeserializeService;

/**
 * Class ShopController
 * @package App\Controller
 * @Route("/api/shop")
 * @Resource(
 *     description="Main desc",
 *     tags={"Shop"},
 *     summariesMap={
 *          "list": "Получить список продаж",
 *          "get": "Получить продажу в магазине",
 *          "post": "Создать продажу в магазине",
 *          "delete": "Удалить продажу в магазине",
 *          "patch": "Обновить продажу в магазине"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список продаж",
 *          "get": "Возвращает продажу по идентификатору",
 *          "post": "Создает новую продажу",
 *          "delete": "Удаляет существующую продажу",
 *          "patch": "Обновляет существующую продажу"
 *     }
 * )
 */
class ShopController extends ApiController
{
    public const ENTITY_CLASS = ShopOrder::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $_entityManager;

    private LoggerInterface $logger;

    /**
     * ShopSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->_entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @SWG\Post(
     *     summary="Создает новый кассовый чек для продажи",
     *     description="Создает новый кассовый чек для продажи",
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="requestId", type="string"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response",
     *                  type="object",
     *                  ref=@Model(type=CashReceipt::class)
     *              )
     *         )
     *     )
     * )
     * @Route("/{id}/cash-receipt/", methods={"POST"})
     *
     * @param $id
     * @param ShopOrderRepository $shopOrderRepository
     * @param EntityManagerInterface $entityManager
     * @param DependenciesService $dependenciesService
     * @param CashRegisterRepository $cashRegisterRepository
     * @param BaseResponse $response
     * @return Response
     * @throws ApiException
     * @throws DBALException
     * @throws ValidationException
     */
    public function createCashReceipt(
        $id,
        ShopOrderRepository $shopOrderRepository,
        CashierScheduleRepository $cashierScheduleRepository,
        EntityManagerInterface $entityManager,
        DependenciesService $dependenciesService,
        CashRegisterRepository $cashRegisterRepository,
        BaseResponse $response
    )
    {
        $shopOrder = $shopOrderRepository->findOneBy(['id' => $id, 'deleted' => false]);

        if (!$shopOrder) {
            throw new ApiException('appointment.not_found');
        }

        if ($shopOrder->getCashReceipt()) {
            return $response
                ->setResponse(['id' => $shopOrder->getCashReceipt()->getId()])
                ->setSerializationContext(['groups' => ['default']])
                ->send();
        }

        $scheduleForCashier = $cashierScheduleRepository->findCurrentCashierScheduleForCashier($this->getUser());
        if (!$scheduleForCashier) {
            throw new ApiException(
                'appointment.cash_receipt.not_found_actual_cashier_shift',
                'ERROR_ACTUAL_CASHIER_SHIFT',
                null,
                400
            );
        }
        $cashRegister = $scheduleForCashier->getCashRegister();

        $cashReceipt = new CashReceipt();

        $total = 0;
        $itemsForCashReceipt = [];
        foreach ($shopOrder->getDocumentProducts() as $shopProductItem) {
            $cashReceiptItem = new ReceiptItem();

            $cashReceiptItem->setCashReceipt($cashReceipt);
            $cashReceiptItem->setProductCode($shopProductItem->getProduct()->getProductCode());
            $cashReceiptItem->setProduct($shopProductItem->getProduct());
            $cashReceiptItem->setStock($shopProductItem->getStock());
            $cashReceiptItem->setPaymentObject($shopProductItem->getProduct()->getPaymentObject());
            $cashReceiptItem->setVatRate($shopProductItem->getProduct()->getVatRate());
            $cashReceiptItem->setPrice($shopProductItem->getPrice());
            $cashReceiptItem->setMeasure($shopProductItem->getMeasure() ?? '');
            $cashReceiptItem->setAmount(round($shopProductItem->getAmount(), 2));
            $cashReceiptItem->setQuantity($shopProductItem->getQuantity());
            $cashReceiptItem->setName($shopProductItem->getProduct()->getName());
            $cashReceiptItem->setPriceWithCharge($shopProductItem->getPrice());

            $itemsForCashReceipt[] = $cashReceiptItem;
            $total += $cashReceiptItem->getAmount();
        }

        # Logger for KORVET-40
        $this->logger->error(sprintf('Cash receipt. ID: %d. Total: %d. ItemsForCashReceipt: %s',
            $id,
            $total,
            \GuzzleHttp\json_encode($itemsForCashReceipt)
        ));

        $cashReceipt->setType(CashReceiptTypeEnum::getItem(CashReceiptTypeEnum::SELL));
        $cashReceipt->setCashRegister($cashRegister);
        $cashReceipt->setCreator($this->getUser());
        $cashReceipt->setTotal(round($total, 2));
        $cashReceipt->setTaxationType($cashRegister->getOrganization()->getTaxationType());
        $cashReceipt->setItems($itemsForCashReceipt);
        $cashReceipt->setDeliveryType(ReceiptDeliveryTypeEnum::getItem(ReceiptDeliveryTypeEnum::PRINT));
        $cashReceipt->setPaymentMethod(PaymentMethodEnum::getItem(PaymentMethodEnum::FULL_PAYMENT));

        $cashReceipt->setPaymentType($shopOrder->getPaymentType());

        $fiscalParameters = new FiscalParameters();
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));
        $cashReceipt->setFiscal($fiscalParameters);

        $dependenciesService->getValidator()->validate($cashReceipt);

        $entityManager->persist($cashReceipt);
        $entityManager->flush();

        $shopOrder->setCashReceipt($cashReceipt);
        $entityManager->persist($shopOrder);
        $entityManager->flush();

        return $response
            ->setResponse(['id' => $cashReceipt->getId()])
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * @return array
     */
    public function getSerializationContextOptions(): array
    {
        return [
            'getList' => ['groups' => ['default']],
            'addItem' => ['groups' => ['default']],
            'getItem' => ['groups' => ['default']],
            'patchItem' => ['groups' => ['default']],
            'updateItem' => ['groups' => ['default']],
        ];
    }

     /**
     * Проверяем остатки на складах, если по какой-то причине не сохранилось нужное кол-во, срабатывает ексепшен
     *
     * @param EntityManagerInterface $entityManager
     * @param array $productStocks
     * @param array|null $products
     */
    private function _beforeUpdateValidationProductStock(EntityManagerInterface $entityManager, array $productStocks, ?array $products)
    {
        foreach ($products as $product_id => $stocks) {
            foreach ($stocks as $stock_id => $quantity) {
                /** @var ProductStock $productStock */
                $productStock = $entityManager->getRepository(ProductStock::class)->findOneBy(
                    ['product' => $product_id, 'stock' => $stock_id]
                );

                if ($productStocks[$product_id] != $productStock->getQuantity()) {
                    throw new InvalidArgumentException('productStock.different_quantity');
                }
            }
        }
    }
}
