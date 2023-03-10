<?php

namespace App\Controller\Leaving;

use App\Controller\Documents\DocumentControllerTrait;
use App\Entity\Cash\CashReceipt;
use App\Entity\Document\DocumentHistory;
use App\Entity\Leaving\Leaving;
use App\Entity\Leaving\LeavingLogs;
use App\Entity\ProductStock;
use App\Entity\Reference\Stock;
use App\Enum\DocumentStateEnum;
use App\Interfaces\ServiceDocumentInterface;
use App\Repository\Cash\CashierScheduleRepository;
use App\Repository\Leaving\LeavingRepository;
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
use App\Repository\Reference\CashRegisterRepository;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Service\HandlerException\Validation\ValidationException;
use App\Service\DeserializeService;

/**
 * Class LeavingController
 * @package App\Controller\Leaving
 * @Route("/api/leaving")
 * @Resource(
 *     description="Main desc",
 *     tags={"Leaving"},
 *     summariesMap={
 *          "list": "???????????????? ???????????? ??????????????",
 *          "get": "???????????????? ??????????",
 *          "post": "?????????????? ??????????",
 *          "delete": "?????????????? ??????????",
 *          "patch": "???????????????? ??????????"
 *     },
 *     descriptionsMap={
 *          "list": "???????????????????? ???????????? ??????????",
 *          "get": "???????????????????? ?????????? ???? ????????????????????????????",
 *          "post": "?????????????? ?????????? ??????????",
 *          "delete": "?????????????? ???????????????????????? ??????????",
 *          "patch": "?????????????????? ???????????????????????? ??????????"
 *     }
 * )
 */
class LeavingController extends ApiController
{
    public const ENTITY_CLASS = Leaving::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;

    /** @var EntityManagerInterface */
    private $_entityManager;

    private $logger;

    /**
     * LeavingSubscriber constructor.
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
     *     summary="?????????????? ?????????? ???????????????? ?????? ?????? ??????????",
     *     description="?????????????? ?????????? ???????????????? ?????? ?????? ??????????",
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="???????????????? ?????????? ??????????????",
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
     * @param LeavingRepository $leavingRepository
     * @param CashierScheduleRepository $cashierScheduleRepository
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
        LeavingRepository $leavingRepository,
        CashierScheduleRepository $cashierScheduleRepository,
        EntityManagerInterface $entityManager,
        DependenciesService $dependenciesService,
        CashRegisterRepository $cashRegisterRepository,
        BaseResponse $response
    )
    {
        $leaving = $leavingRepository->findOneBy(['id' => $id, 'deleted' => false]);

        if (!$leaving) {
            throw new ApiException('leaving.not_found');
        }

        if ($leaving->getState()->code !== DocumentStateEnum::REGISTERED) {
            throw new ApiException(
                'leaving.must_be_registered',
                'leaving.must_be_registered',
                null,
                400
            );
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

        if ($leaving->getCashReceipt()) {
            return $response
                ->setResponse(['id' => $leaving->getCashReceipt()->getId()])
                ->setSerializationContext(['groups' => ['default']])
                ->send();
        }

        $cashReceipt = new CashReceipt();

        $total = 0;
        $itemsForCashReceipt = [];
        foreach ($leaving->getProductItems() as $leavingProductItem) {
            $cashReceiptItem = new ReceiptItem();

            $cashReceiptItem->setCashReceipt($cashReceipt);
            $cashReceiptItem->setProductCode($leavingProductItem->getProduct()->getProductCode());
            $cashReceiptItem->setProduct($leavingProductItem->getProduct());
            $cashReceiptItem->setStock($leavingProductItem->getStock());
            $cashReceiptItem->setPaymentObject($leavingProductItem->getProduct()->getPaymentObject());
            $cashReceiptItem->setVatRate($leavingProductItem->getProduct()->getVatRate());
            $cashReceiptItem->setPrice($leavingProductItem->getPrice());
            $cashReceiptItem->setMeasure($leavingProductItem->getMeasure() ?? '');
            $cashReceiptItem->setAmount(round($leavingProductItem->getAmount(), 2));
            $cashReceiptItem->setQuantity($leavingProductItem->getQuantity());
            $cashReceiptItem->setName($leavingProductItem->getProduct()->getName());
            $cashReceiptItem->setPriceWithCharge($leavingProductItem->getPriceWithCharge());

            $itemsForCashReceipt[] = $cashReceiptItem;
            $total += $cashReceiptItem->getAmount();
        }
        foreach ($itemsForCashReceipt as $key => $product){
            if ($product ->getPrice() ==0) {
                unset($itemsForCashReceipt[$key]);
            }
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

        if (is_null($leaving->getPaymentType()->code)) {
            throw new ApiException(
                'appointment.cash_receipt.payment_type_not_null_error',
                'appointment.cash_receipt.payment_type_not_null_error',
                null,
                400
            );
        }

        $cashReceipt->setPaymentType($leaving->getPaymentType());

        $fiscalParameters = new FiscalParameters();
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));
        $cashReceipt->setFiscal($fiscalParameters);

        $dependenciesService->getValidator()->validate($cashReceipt);

        $entityManager->persist($cashReceipt);
        $entityManager->flush();

        $leaving->setCashReceipt($cashReceipt);
        $entityManager->persist($leaving);
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
            'getList' => ['groups' => ['default', "permission.doctor", 'api.owner', 'api.pet', "permission.doctor"]],
            'addItem' => ['groups' => ['default', 'api.leaving', 'api.owner', 'api.pet', "permission.doctor"]],
            'getItem' => ['groups' => ['default', 'api.leaving', 'api.owner', 'api.pet', "permission.doctor"]],
            'patchItem' => ['groups' => ['default', 'api.leaving', 'api.owner', 'api.pet', "permission.doctor"]],
            'updateItem' => ['groups' => ['default', 'api.leaving', 'api.owner', 'api.pet', "permission.doctor"]],
        ];
    }

    /**
     * @Route("/{id}/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"PATCH"})
     * @Operation("patch")
     * @SWG\Patch(
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="?????????????????????????? ?????????????????????? ????????????????",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="entity",
     *          in="body",
     *          description="Json-???????????? ?? ?????????????? ?????????????????????? ????????????????, ?????????????????? ???????????????? ???????????????? ????????????????"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="???????????????? ?????????? ??????????????, ?? ???????????????????? ????????????????
     *     ???????????? ???????????????????????? ??????????????",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="???????????? ???????????????? ????????????????????",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *
     *          examples={
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
     *                  "errors": {0: {"message": "?????????? ????????????", "stringCode": "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param string $id
     * @param Request $request
     * @param UpdateItemService $service
     * @param SerializationContextFetcher $serializationContextFetcher
     * @return Response
     * @throws ApiException
     * @throws ValidationException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function patchItemAction(string $id, Request $request, UpdateItemService $service, SerializationContextFetcher $serializationContextFetcher): Response
    {
        $serializationContext = $this->getSerializationContext('patchItem');

        $entityClass = $this instanceof DynamicEntityClassControllerInterface ? $this->getEntityClass() : self::ENTITY_CLASS;

        $isDefinedDtoClass = defined(get_class($this) . '::DTO_CLASS');
        if ($groups = $serializationContextFetcher->getSerializationGroups('patch', $isDefinedDtoClass ? self::DTO_CLASS : $entityClass)) {
            if (!isset($serializationContext['groups'])) {
                $serializationContext['groups'] = [];
            }

            $serializationContext['groups'] = array_merge($serializationContext['groups'], $groups);
        }

        $outputSerializationContext = $serializationContext;
        $outputSerializationContext['groups'] = $serializationContext['groups'] ?? [];
        $outputSerializationContext['groups'][] = 'detail';

        if ($isDefinedDtoClass) {
            $service->setDtoClass(self::DTO_CLASS);
        }

        $content = $this->_validationAndChangeData(json_decode($request->getContent(), true));

        $result = $service->update($id, $content, $entityClass, [ValidationService::GROUP_UPDATE, ValidationService::GROUP_DEFAULT], $outputSerializationContext, $serializationContext);

        return $result->send();
    }

    /**
     * ???????????????? ???????????? ??????????????????
     *
     * @Route("/{number}/state/", methods={"POST"})
     * @SWG\Post(
     *
     * @SWG\Parameter(
     *      name="number",
     *      in="path",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *      description="?????????? ??????????????????",
     *  ),
     *
     * @SWG\Parameter(
     *         in="body",
     *         name="???????????? ?????? ???????????????????? ?????????????? ??????????????????",
     *         @SWG\Schema(ref=@Model(type=DocumentStateEnum::class))
     *  ),
     *
     * @SWG\Response(
     *          response=200,
     *          description="???????????????? ?????????? ??????????????",
     *     )
     * )
     *
     *          examples={
     *              "application/json":{
     *                  "status": true,
     *                  "errors": null,
     *                  "response": {
     *                      "state": {
     *                          "code": "DRAFT",
     *                          "title": "????????????????"
     *                          },
     *                      "date": "26.09.2019 13:10:00"
     *                  }
     *              }
     *          }
     * @param int $number
     * @param BaseResponse $response
     * @param Request $request
     * @param DocumentService $documentService
     * @param DeserializeService $deserializeService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ApiException
     * @throws DBALException
     */
    public function changeState(
        $number,
        BaseResponse $response,
        Request $request,
        DocumentService $documentService,
        DeserializeService $deserializeService,
        EntityManagerInterface $entityManager
    ): Response
    {

        /** @var ServiceDocumentInterface $document */
        $document = $documentService->addDocument($number, $this::ENTITY_CLASS);

        if (!$document) {
            throw new ApiException('document.not_found', 'document.not_found', null, Response::HTTP_BAD_REQUEST);
        }

        /** @var DocumentStateEnum $state */
        $state = $deserializeService->deserialize($request->getContent(), DocumentStateEnum::class, 'json');

        if (!$state instanceof DocumentStateEnum) {
            throw new ApiException('document.state.not_found', 'document.state.not_found', null, Response::HTTP_BAD_REQUEST);
        }

        $log = json_encode($document) . "; Change document ID: $number ";

        $entityManager->getConnection()->beginTransaction();
        //?????? ???????????????? ?? ???????????? ????????????????????????
        if (($document->getDocument()->getState()->code === DocumentStateEnum::DRAFT || $document->getDocument()->getState()->code === DocumentStateEnum::ERROR) &&
            $state->code === DocumentStateEnum::REGISTERED
        ) {
            $document->setState($state);
            /** @var DocumentHistory[] $historyArray */
            $historyArray = $document->getHistory();
            $log .= "DRAFT to REGISTERED; ";

            foreach ($historyArray as $history) {
                if ($history->getQuantity() == 0) {
                    throw new ApiException('productStock.quantity_null', 'productStock.quantity_null', null, Response::HTTP_BAD_REQUEST);
                }
                if ($history->getProduct()->getPrice() == 0) {
                    if ($history->getProduct()->getBudgetDrug() === false){
                        throw new ApiException('productStock.budget_price', 'productStock.budget_price', null, Response::HTTP_BAD_REQUEST);
                    }
                }
            }

            $productStocks = [];

            try {
                $products = [];

                # ?????????????????? ???????????? ?? ???????????????????? ???? ?????????? ?? ??.??. ?? ???????? ?????????????? ?????? ???????????? ???? ????????????
                foreach ($historyArray as $history) {
                    $product_id = $history->getProduct()->getId();
                    $stock_id = $history->getStock()->getId();

                    if (!isset($products[$product_id])) $products[$product_id] = [];

                    if (!isset($products[$product_id][$stock_id])) $products[$product_id][$stock_id] = 0;

                    $products[$product_id][$stock_id] += $history->getQuantity();
                }

                $array_for_logs = [];

                foreach ($products as $product_id => $stocks) {
                    foreach ($stocks as $stock_id => $quantity) {
                        /** @var ProductStock $productStock */
                        $productStock = $entityManager->getRepository(ProductStock::class)->findOneBy(
                            ['product' => $product_id, 'stock' => $stock_id]
                        );

                        $stock_quantity = $productStock->getQuantity() + $quantity;

                        $array_for_logs[] = [
                            'Stock' => $productStock->getStock()->getName(),
                            'Product' => $productStock->getProduct()->getName(),
                            'Product Stock Quantity' => $productStock->getQuantity(),
                            'Product Stock ID' => $productStock->getStock()->getId(),
                            'Quantity history' => $quantity,
                            'Quantity stock' => $productStock->getQuantity(),
                            'Quantity current' => $stock_quantity,
                        ];

                        $productStocks[$productStock->getProduct()->getId()] = $stock_quantity;

                        //?????????????????????? ???? ?????????? ????????????????????
                        $productStock->setQuantity($stock_quantity);

                        if ($productStock->getQuantity() !== 0) {
                            $productStock->getProduct()->setExistQuantity(true);
                        }
                    }
                }

                foreach ($array_for_logs as $array_for_log) {
                    $dataAttributes = array_map(function ($value, $key) {
                        return "$key:$value";
                    }, array_values($array_for_log), array_keys($array_for_log));
                    $log .= implode('; ', $dataAttributes) . "\n";
                }

                $this->_beforeUpdateValidationProductStock($entityManager, $productStocks, $products);
               
                $entityManager->getConnection()->commit();

            } catch (Exception $exception) {
                $this->logger->error($log);
                $entityManager->getConnection()->rollBack();
                $document->removeHistory();
                $document->setState(DocumentStateEnum::getItem(DocumentStateEnum::ERROR));
                $document->addError($exception->getMessage());
                throw new ApiException($exception->getMessage(), '', null, $exception->getCode());
            }
        } else if ($document->getDocument()->getState()->code === DocumentStateEnum::REGISTERED && $state->code === DocumentStateEnum::DRAFT) {
            //?????????????????? ?????????????? ?? ???????????? ??????????????????
            try {
                $history = $document->getHistory();
                $document->setState($state);

                //???? ?????????????????????? ?????????? ????????????????, ???????? ?????????? ?????????????? ?????? ???? ????????????????
                $this->rollback($history, $entityManager);
                //                ???????? ?????????? ?? ?????? ????????????, ???? ???? ???????????????????? - ??????????????
                if ($document->getDocument() instanceof Leaving) {
                    /** @var Leaving $leaving */
                    $leaving = $document->getDocument();

                    $leaving->setDateEnd(null);

                    /** @var CashReceipt $cashReceipt */
                    $cashReceipt = $leaving->getCashReceipt();
                    if ($cashReceipt) {
                        if (strcasecmp($cashReceipt->getFiscal()->getState(), FiscalReceiptStateEnum::DONE) != 0) {
                            $leaving->removeCashReceipt();
                        }
                    }
                }

                $entityManager->getConnection()->commit();
            } catch (Exception $exception) {
                $entityManager->getConnection()->rollBack();
                $document->addError($exception->getMessage());
                throw new ApiException($exception->getMessage());
            }

        } elseif ($document->getDocument()->getState()->code === DocumentStateEnum::ERROR && $state->code === DocumentStateEnum::DRAFT) {
            $document->setState($state);
            $document->clearErrors();
            $entityManager->getConnection()->commit();


        } elseif ($document->getDocument()->getState()->code === DocumentStateEnum::REGISTERED && $state->code === DocumentStateEnum::REGISTERED) {
            throw new ApiException(
                'document.state.already_registered',
                'document.state.not_found',
                null,
                Response::HTTP_BAD_REQUEST
            );
        }

        $log .= " End";

        $this->logger->error($log);

        return $response
            ->setResponse([
                'state' => $document->getDocument()->getState(),
                'date' => $document->getDate() // ???????? ?????????????????????? ??????????????????
            ])
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * ???????????????????? ?????? ?????????????????? ?????????????? ????????????
     * @Route("/{id}/logs/", methods={"GET"})
     * @SWG\Post(
     * @SWG\Response(
     *          response=200,
     *          description="???????????????? ?????????? ??????????????",
     *     )
     * )
     *
     *          examples={
     *              "application/json":{
     *                  "status": true,
     *                  "errors": null,
     *                  "response": {}
     *              }
     *          }
     * @param int $id
     * @param BaseResponse $response
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ApiException
     */
    public function getLogs(
        $id,
        BaseResponse $response,
        EntityManagerInterface $entityManager
    ): Response
    {
        $leaving = $entityManager->getRepository(Leaving::class)->findOneBy([
            'id' => $id
        ]);

        if (is_null($leaving)) {
            throw new ApiException('leaving.not_found');
        }

        $logs = $entityManager->getRepository(LeavingLogs::class)->findBy([
            'leaving' => $leaving
        ]);

        return $response
            ->setResponse($logs)
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * ???? ?????????????????????? ?????????? ????????????????, ???????? ?????????? ?????????????? ?????? ???? ????????????????
     * @param array|null $objects
     * @param EntityManagerInterface $entityManager
     */
    protected function rollback(?array $objects, EntityManagerInterface $entityManager): void
    {
        if ($objects === null) {
            return;
        }

        $log = json_encode($objects) . "; Rollback Leaving ";

        foreach ($objects as $history) {
            /** @var ProductStock $productStock */
            $productStock = $entityManager->getRepository(ProductStock::class)->findOneBy(
                ['product' => $history->getProduct()->getId(), 'stock' => $history->getStock()->getId()]
            );

            if (!$productStock) {
                continue;
            }
            $quantity = $productStock->getQuantity() - $history->getQuantity();

            $log .= " Stock: " . $history->getStock()->getName() . "; Product: " . $history->getProduct()->getId() . "; 
                    Product Stock Quantity: " . $productStock->getQuantity() . "; History Quantity: " . $history->getQuantity() . "; 
                    Set Quantity: $quantity";
            //??????????????, ?? ?????????????????????? ???????????? ???????? ?????????????????????????? ????????????????
            $productStock->setQuantity($quantity);
        }

        $log .= " End";

        $this->logger->error($log);
    }

    /**
     * @param array $content
     * @return string
     * @throws ApiException
     */
    private function _validationAndChangeData(array $content): string
    {
        if (isset($content['productItems']) && is_array($content['productItems']) && count($content['productItems']) > 0) {

            $products = [];

            foreach ($content['productItems'] as $index => $productItem) {
                if (isset($productItem['product'])) {
                    if (isset($productItem['paymentObject']) && $productItem['paymentObject'] === PaymentObjectEnum::SERVICE) {
                        $items = [];

                        foreach ($productItem['items'] as $item) {
                            $product_id = $item['product']['id'];

                            if (isset($items[$product_id])) {
                                if ($item['stock']['id'] === $items[$product_id]['stock']['id']) {
                                    $items[$product_id]['quantity'] += $item['quantity'];
                                    continue;
                                }
                            }

                            $items[$product_id] = $item;
                        }
                        $productItem['items'] = array_values($items);
                        $products[] = $productItem;
                    } else {

                        if (!is_null($productItem['stock'])) {
                            $this->_validationStock($productItem, $content);
                        }

                        $product = $productItem['product'];

                        $product_id = $product['id'];

                        if (isset($products[$product_id])) {
                            if ($productItem['stock']['id'] === $products[$product_id]['stock']['id']) {
                                $products[$product_id]['quantity'] += $productItem['quantity'];
                                continue;
                            }
                        }

                        $products[$product_id] = $productItem;
                    }
                }
            }

            if (count($content['productItems']) > 0 && count($products) === 0) {
                throw new ApiException('???????????????????????? ???????????????? ??????????????/??????????');
            }

            $productItems = array_values($products);

            # ?????????????? ???????????????? ???????????????? ??????????????
            foreach ($productItems as $i => $value) {
                if (isset($value['product'])) {
                    foreach ($value['product'] as $a => $item) {
                        if ($a === "productStock") {
                            unset($productItems[$i]['product'][$a]);
                        }
                    }
                }
            }

            $content['productItems'] = $productItems;
        }

        return json_encode($content);
    }

    /**
     * @param array $productItem
     * @throws ApiException
     */
    private function _validationStock(array $productItem, array $content): void
    {
        if (!isset($productItem['stock']) || !isset($productItem['stock']['id'])) {
            throw new ApiException(sprintf('?? ???????????? %s ?????????????????????? ???????????? ??????????', $productItem['product']['name']));
        }

        $stock_id = $productItem['stock']['id'];

        $stock = $this->_entityManager->getRepository(Stock::class)->findOneBy(['id' => $stock_id]);

        if (!$stock) {
            throw new ApiException(sprintf('?????????? ???? %s ???? ????????????', $stock_id));
        }

        if (!is_null($stock->getUnit()) && $content['paymentState']['code'] !== PaymentStateEnum::PAID) {
            $unit = $this->getUser()->getUnit();

            if (is_null($unit)) {
                throw new ApiException('?? ???????????????????????? ?????? ???????????????? ?? ??????????????');
            }

            if ($stock->getUnit()->getId() !== $unit->getId()) {
                throw new ApiException(sprintf(
                    '???????????????? ?????????? %s ???? ???????????????? ?? ?????????????? %s',
                    $stock->getName(),
                    $unit->getName()
                ));
            }
        }
    }

    /**
     * ?????????????????? ?????????????? ???? ??????????????, ???????? ???? ??????????-???? ?????????????? ???? ?????????????????????? ???????????? ??????-????, ?????????????????????? ????????????????
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
