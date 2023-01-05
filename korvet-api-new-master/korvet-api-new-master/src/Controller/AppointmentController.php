<?php

namespace App\Controller;

use App\Controller\Documents\DocumentControllerTrait;
use App\Entity\Appointment\Appointment;
use App\Entity\Appointment\AppointmentLogs;
use App\Entity\Cash\CashReceipt;
use App\Entity\Document\DocumentHistory;
use App\Entity\ProductStock;
use App\Entity\Reference\Stock;
use App\Enum\DocumentStateEnum;
use App\Interfaces\ServiceDocumentInterface;
use App\Repository\Appointment\AppointmentRepository;
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
use App\Repository\Reference\CashRegisterRepository;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Service\HandlerException\Validation\ValidationException;
use App\Service\DeserializeService;
use App\Entity\Embeddable\ProductCode;

/**
 * Class AppointmentController
 * @package App\Controller
 * @Route("/api/appointment")
 * @Resource(
 *     description="Main desc",
 *     tags={"Appointment"},
 *     summariesMap={
 *          "list": "Получить список приемов",
 *          "get": "Получить прием",
 *          "post": "Создать прием",
 *          "delete": "Удалить прием",
 *          "patch": "Обновить прием"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список приемов",
 *          "get": "Возвращает прием по идентификатору",
 *          "post": "Создает новый прием",
 *          "delete": "Удаляет существующий прием",
 *          "patch": "Обновляет существующий прием"
 *     }
 * )
 */
class AppointmentController extends ApiController
{
    public const ENTITY_CLASS = Appointment::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $_entityManager;

    private LoggerInterface $logger;

    /**
     * AppointmentSubscriber constructor.
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
     *     summary="Создает новый кассовый чек для приема",
     *     description="Создает новый кассовый чек для приема",
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
     * @param AppointmentRepository $appointmentRepository
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
        AppointmentRepository $appointmentRepository,
        CashierScheduleRepository $cashierScheduleRepository,
        EntityManagerInterface $entityManager,
        DependenciesService $dependenciesService,
        CashRegisterRepository $cashRegisterRepository,
        BaseResponse $response
    )
    {
        $appointment = $appointmentRepository->findOneBy(['id' => $id, 'deleted' => false]);

        if (!$appointment) {
            throw new ApiException('appointment.not_found');
        }

        if ($appointment->getState()->code !== DocumentStateEnum::REGISTERED) {
            throw new ApiException(
                'appointment.must_be_registered',
                'appointment.must_be_registered',
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

        if ($appointment->getCashReceipt()) {
            return $response
                ->setResponse(['id' => $appointment->getCashReceipt()->getId()])
                ->setSerializationContext(['groups' => ['default']])
                ->send();
        }

        $cashReceipt = new CashReceipt();

        $total = 0;
        $itemsForCashReceipt = [];
        foreach ($appointment->getProductItems() as $appointmentProductItem) {
            $cashReceiptItem = new ReceiptItem();

            $cashReceiptItem->setCashReceipt($cashReceipt);
            $cashReceiptItem->setProductCode($appointmentProductItem->getProduct()->getProductCode());
            $cashReceiptItem->setProduct($appointmentProductItem->getProduct());
            $cashReceiptItem->setStock($appointmentProductItem->getStock());
            $cashReceiptItem->setPaymentObject($appointmentProductItem->getProduct()->getPaymentObject());
            $cashReceiptItem->setVatRate($appointmentProductItem->getProduct()->getVatRate());
            $cashReceiptItem->setPrice($appointmentProductItem->getPrice());
            $cashReceiptItem->setMeasure($appointmentProductItem->getMeasure() ?? '');
            $cashReceiptItem->setAmount(round($appointmentProductItem->getAmount(), 2));
            $cashReceiptItem->setQuantity($appointmentProductItem->getQuantity());
            $cashReceiptItem->setName($appointmentProductItem->getProduct()->getName());
            $cashReceiptItem->setPriceWithCharge($appointmentProductItem->getPriceWithCharge());

            $itemsForCashReceipt[] = $cashReceiptItem;
            $total += $cashReceiptItem->getAmount();
        }
        foreach ($itemsForCashReceipt as $key => $product){
            if ($product ->getPrice() ==0) {
               unset($itemsForCashReceipt[$key]);
            }

        }

        //Отбор проб
        foreach ($appointment->getProbeSamplings() as $probeSampling) {
            foreach ($probeSampling->getProbeItems() as $probeItem) {
                $cashReceiptItem = new ReceiptItem();

                $cashReceiptItem->setCashReceipt($cashReceipt);
                $cashReceiptItem->setProductCode(new ProductCode());
                $cashReceiptItem->setPaymentObject(PaymentObjectEnum::getItem(PaymentObjectEnum::SERVICE));
                $cashReceiptItem->setVatRate($probeItem->getProbe()->getVatRate());
                $cashReceiptItem->setPrice($probeItem->getPrice());
                $cashReceiptItem->setMeasure('шт');
                $cashReceiptItem->setAmount(round($probeItem->getAmount(), 2));
                $cashReceiptItem->setQuantity($probeItem->getQuantity());
                $cashReceiptItem->setName('Отбор пробы: '.$probeItem->getProbe()->getName());
                $cashReceiptItem->setPriceWithCharge($probeItem->getPrice());

                $itemsForCashReceipt[] = $cashReceiptItem;
                $total += $cashReceiptItem->getAmount();

                foreach ($probeItem->getResearchDocuments() as $researchItem) {
                    if ($researchItem->getPrice() == 0) {
                        continue;
                    }
                    $cashResearchItem = new ReceiptItem();
        
                    $cashResearchItem->setCashReceipt($cashReceipt);
                    $cashResearchItem->setProductCode(new ProductCode());
                    $cashResearchItem->setPaymentObject(PaymentObjectEnum::getItem(PaymentObjectEnum::SERVICE));
                    $cashResearchItem->setVatRate($probeItem->getProbe()->getVatRate());
                    $cashResearchItem->setPrice($researchItem->getPrice());
                    $cashResearchItem->setMeasure('шт');
                    $cashResearchItem->setAmount(round($researchItem->getPrice(), 2));
                    $cashResearchItem->setQuantity(1);
                    $cashResearchItem->setName('Исследование: '.$researchItem->getResearch()->getName());
                    $cashResearchItem->setPriceWithCharge($researchItem->getPrice());
        
                    $itemsForCashReceipt[] = $cashResearchItem;
                    $total += $cashResearchItem->getAmount();
                }
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

        if (is_null($appointment->getPaymentType()->code)) {
            throw new ApiException(
                'appointment.cash_receipt.payment_type_not_null_error',
                'appointment.cash_receipt.payment_type_not_null_error',
                null,
                400
            );
        }

        $cashReceipt->setPaymentType($appointment->getPaymentType());

        $fiscalParameters = new FiscalParameters();
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));
        $cashReceipt->setFiscal($fiscalParameters);

        $dependenciesService->getValidator()->validate($cashReceipt);

        $entityManager->persist($cashReceipt);
        $entityManager->flush();

        $appointment->setCashReceipt($cashReceipt);
        $entityManager->persist($appointment);
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
            'addItem' => ['groups' => ['default', 'api.appointment', 'api.owner', 'api.pet', "permission.doctor"]],
            'getItem' => ['groups' => ['default', 'api.appointment', 'api.owner', 'api.pet', "permission.doctor"]],
            'patchItem' => ['groups' => ['default', 'api.appointment', 'api.owner', 'api.pet', "permission.doctor"]],
            'updateItem' => ['groups' => ['default', 'api.appointment', 'api.owner', 'api.pet', "permission.doctor"]],
        ];
    }

    /**
     * @Route("/{id}/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"PATCH"})
     * @Operation("patch")
     * @SWG\Patch(
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор обновляемой сущности",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="entity",
     *          in="body",
     *          description="Json-объект с данными обновляемой сущности, допустима передача сущности частично"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные обновленного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка операции обновления",
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
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode": "ERROR_STRING_CODE", "relatedField": null}}
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
     * Обновить статус документа
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
     *      description="Номер документа",
     *  ),
     *
     * @SWG\Parameter(
     *         in="body",
     *         name="Данные для обновления статуса документа",
     *         @SWG\Schema(ref=@Model(type=DocumentStateEnum::class))
     *  ),
     *
     * @SWG\Response(
     *          response=200,
     *          description="Успешный ответ сервиса",
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
     *                          "title": "Черновик"
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
        //был черновик и теперь регестрируем
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

                # формируем массив с продуктами из услуг и т.п. в один элемент для снятие со склада
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

                        //поступление по этому прибавляем
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
        } elseif ($document->getDocument()->getState()->code === DocumentStateEnum::REGISTERED && $state->code === DocumentStateEnum::DRAFT) {
            //переводим обратно в статус черновика
            try {
                $history = $document->getHistory();
                $document->setState($state);

                //по действующей схеме откатить, если ранне конечно что то записали
                $this->rollback($history, $entityManager);
                //                Если прием и чек создан, но не распечатан - удаляем
                if ($document->getDocument() instanceof Appointment) {
                    /** @var Appointment $appointment */
                    $appointment = $document->getDocument();

                    $appointment->setDateEnd(null);

                    /** @var CashReceipt $cashReceipt */
                    $cashReceipt = $appointment->getCashReceipt();
                    if ($cashReceipt) {
                        if (strcasecmp($cashReceipt->getFiscal()->getState(), FiscalReceiptStateEnum::DONE) != 0) {
                            $appointment->removeCashReceipt();
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
                'date' => $document->getDate() // дата регистрации документа
            ])
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * Возвращает лог изменений статуса приема
     * @Route("/{id}/logs/", methods={"GET"})
     * @SWG\Post(
     * @SWG\Response(
     *          response=200,
     *          description="Успешный ответ сервиса",
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
        int                    $id,
        BaseResponse           $response,
        EntityManagerInterface $entityManager
    ): Response
    {
        $appointment = $entityManager->getRepository(Appointment::class)->findOneBy([
            'id' => $id
        ]);

        if (is_null($appointment)) {
            throw new ApiException('appointment.not_found');
        }

        $logs = $entityManager->getRepository(AppointmentLogs::class)->findBy([
            'appointment' => $appointment
        ]);

        return $response
            ->setResponse($logs)
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * по действующей схеме откатить, если ранне конечно что то записали
     * @param array|null $objects
     * @param EntityManagerInterface $entityManager
     */
    protected function rollback(?array $objects, EntityManagerInterface $entityManager): void
    {
        if ($objects === null) {
            return;
        }

        $log = json_encode($objects) . "; Rollback Appointment ";

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
            //отнимем, у перемещения должны быть отрицательные значения
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
                throw new ApiException('Некорректная проверка товаров/услуг');
            }

            $productItems = array_values($products);

            # удаляем стоковые значения товаров
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
            throw new ApiException(sprintf('К товару %s некорректно указан склад', $productItem['product']['name']));
        }

        $stock_id = $productItem['stock']['id'];

        $stock = $this->_entityManager->getRepository(Stock::class)->findOneBy(['id' => $stock_id]);

        if (!$stock) {
            throw new ApiException(sprintf('Склад по %s не найден', $stock_id));
        }

        if (!is_null($stock->getUnit()) && $content['paymentState']['code'] !== PaymentStateEnum::PAID) {
            $unit = $this->getUser()->getUnit();

            if (is_null($unit)) {
                throw new ApiException('У пользователя нет привязки к клинике');
            }

            if ($stock->getUnit()->getId() !== $unit->getId()) {
                throw new ApiException(sprintf(
                    'Выбраный склад %s не привязан к клинике %s',
                    $stock->getName(),
                    $unit->getName()
                ));
            }
        }
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
