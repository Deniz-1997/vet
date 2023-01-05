<?php

namespace App\Packages\EventSubscriber;


use App\Entity\Appointment\AppointmentFormTemplate;
use App\Entity\Leaving\Leaving;
use App\Entity\Leaving\LeavingLogs;
use App\Entity\ProductStock;
use App\Entity\Reference\FormTemplate;
use App\Entity\Reference\Leaving\LeavingProductItem;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Entity\User\User;
use App\Enum\DocumentStateEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Exception\ApiException;
use App\Service\DeserializeService;
use App\Packages\DBAL\Types\PaymentStateEnum;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;

class LeavingSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var User|null */
    private $currentUser = null;
    /** @var DeserializeService */
    private $deserializeService;
    /** @var SerializerInterface */
    private $serializer;
    /** @var String */
    private $beforeState;
    /** @var String */
    private $logger;

    /**
     * LeavingSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param DeserializeService $deserializeService
     * @param SerializerInterface $serializeService
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        DeserializeService $deserializeService,
        SerializerInterface $serializeService,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $token = $tokenStorage->getToken();
        $this->currentUser = $token ? $token->getUser() : null;
        $this->deserializeService = $deserializeService;
        $this->serializer = $serializeService;
        $this->logger = $logger;
    }

    /**
     * @param EventRequest $event
     * @throws \Exception
     */
    public function onCreateLeaving(EventRequest $event)
    {

        /** @var Leaving $leaving */
        $leaving = $event->getData();
        /** @var User $user */
        $user = $this->currentUser instanceof User ? $this->currentUser : null;

        $log = 'Data: '.json_encode($leaving).'; Change leaving ID: ' . $leaving->getId() . ' ';

        if ($leaving->getUser()) {
            // устанавливаем клинику из специалиста
            $leaving->setUnit($leaving->getUser()->getUnit());
        } elseif ($this->currentUser instanceof User) {
            // устанавливаем клинику из текущего пользователя
            $leaving->setUnit($this->currentUser->getUnit());
        }

        $log .= " Write-off of goods from the warehouse: ";

        /** @var LeavingProductItem $leavingProductItem */
        foreach ($leaving->getProductItems() as $leavingProductItem) {
            $leavingProductItem->setCreator($user);

            // списание товара со склада
            /** @var Product $product */
            $product = $leavingProductItem->getProduct();
            /** @var Stock $stock */
            $stock = $leavingProductItem->getStock();
            /** @var ProductStock $productStock */
            $productStock = $this->entityManager->getRepository(ProductStock::class)->findOneBy([
                'product' => $product,
                'stock' => $stock,
            ]);

            if (!$productStock && $product->getPaymentObject()->code !== PaymentObjectEnum::SERVICE) {
                throw new ApiException(sprintf(
                    'Товар %s не найден на складе %s',
                    $product ? $product->getName() : '',
                    $stock ? $stock->getName() : ''
                ));
            }

            $quantity = $productStock->getQuantity() - $leavingProductItem->getQuantity();

            $log .= " Stock: " . $stock->getName() . "; Product: " . $product->getId() . "; Product Stock Quantity: " . $productStock->getQuantity() . "; 
            Leaving Product Item Quantity: " . $leavingProductItem->getQuantity() . "; Quantity: $quantity";

            $productStock->setQuantity($quantity);
            $this->entityManager->persist($productStock);
        }

        $log .= " End.";

        # Logger for KORVET-40
        $this->logger->error($log);

        $leavingLogs = new LeavingLogs();
        $leavingLogs->setLeaving($leaving);
        $leavingLogs->setLeavingStatus($leaving->getLeavingStatus());
        $leavingLogs->setUser($user);
        $leavingLogs->setDate(new \DateTime());
        $this->entityManager->persist($leavingLogs);

        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function onChangeLeaving(EventRequest $event)
    {
        $eventData = $event->getData();

        $requestDataJson = json_decode($eventData['content'], true);
        /** @var Leaving $leaving */
        $leaving = $this->entityManager->find($eventData['objectName'], $eventData['id']);
        //Проверка последнего сохранения
         /** @var Leaving $leaving */
         $leaving = $this->entityManager->find($eventData['objectName'], $eventData['id']);
         //Проверка последнего сохранения
         $currentLastUpdate =  isset($requestDataJson['lastUpdate']) ? new \DateTime($requestDataJson['lastUpdate']) : null;
         $lastLeavingUpdate =  $this->getLastLeavingUpdate($leaving);
         if ($lastLeavingUpdate !== null && $currentLastUpdate!==null && $lastLeavingUpdate != $currentLastUpdate) {
             throw new ApiException('Выезд уже был отредактирован, обновите страницу перед сохранением','LEAVING_CHANGED',null, 400);
         }
        
        if (isset($requestDataJson['appointmentFormTemplate'])) {
            foreach ($requestDataJson['appointmentFormTemplate'] as $formTemplateKey => $appointmentTemplate) {
                /** @var FormTemplate $template */
                $template = $this->entityManager->getRepository(FormTemplate::class)->findOneBy(['id' => $appointmentTemplate['formTemplate']['id']]);
                if ($template->isDeleted()) {
                    throw new ApiException(
                        'formTemplate.attempt_to_use_deleted_formTemplate_in_leaving',
                        '',
                        null,
                        400,
                        ['templateName' => $template->getName()]);
                }
                foreach ($appointmentTemplate['formFieldValues'] as $formFieldKey => $formFieldValue) {
                    $value = strval($formFieldValue['value']);
                    $requestDataJson['appointmentFormTemplate'][$formTemplateKey]['formFieldValues'][$formFieldKey]['value'] = $value;
                }
            }
            $event->setData(json_encode($requestDataJson));

        }

       


        $LeavingLogs = new LeavingLogs();
        $LeavingLogs->setLeaving($leaving);
        $LeavingLogs->setLeavingStatus($leaving->getLeavingStatus());
        $LeavingLogs->setUser($this->currentUser);
        $LeavingLogs->setDate(new \DateTime());
        $this->entityManager->persist($LeavingLogs);

        $log = 'Change leaving in subscriber ID: ' . $eventData['id'] . ' ';

        // Если из выезда удалили шаблон, уменьшаем счетчик использований на 1
        /** @var AppointmentFormTemplate $appointmentFormTemplate */
        foreach ($leaving->getAppointmentFormTemplate() as $appointmentFormTemplate) {
            /** @var FormTemplate $formTemplate */
            $formTemplate = $appointmentFormTemplate->getFormTemplate();
            $deleted = true;
            foreach ($requestDataJson['appointmentFormTemplate'] as $item) {
                if ($item['formTemplate']['id'] == $formTemplate->getId()) {
                    $deleted = false;
                    break;
                }
            }
            if ($deleted) {
                $formTemplate->setLeavingCount($formTemplate->getLeavingCount() - 1);
                $this->entityManager->persist($formTemplate);
            }
        }

        //Если номенклатура не меняется - ничего не делаем
        if (!isset($requestDataJson['productItems'])) {
            return;
        }

        // Если чек распечатан и прием оплачен, то номенклатуры и данные об оплате не изменяются.
        if ($leaving->getPaymentState()->code === PaymentStateEnum::PAID
            && $leaving->getCashReceipt()
            && $leaving->getCashReceipt()->getFiscal()->getState() === FiscalReceiptStateEnum::DONE
        ) {
        // TODO KRVT-12126 https://portal.web-slon.ru/company/personal/user/570/tasks/task/view/12126/
        // Доработать исключение возможности изменять номенклатуры и данные об оплате
            return;
        }

        if ($leaving->getState()->code === DocumentStateEnum::REGISTERED &&
            $requestDataJson['state']['code'] === DocumentStateEnum::DRAFT) {
            throw new ApiException(
                'document.state.already_registered',
                'document.state.not_found',
                null,
                Response::HTTP_BAD_REQUEST
            );

        }

        $log .= "Delete product: ";

        // Удаление товаров из услуг перед обновлением приема
        /** @var LeavingProductItem $item */
        foreach ($leaving->getProductItems() as $item) {
            if ($item->getParent()) {
                $log .= "  " . $item->getId() . " ";
                $this->entityManager->remove($item);
                $this->entityManager->flush();
            }
        }

        $log .= ";";

        $deniedProductItems = $leaving->getProductItems()->filter((function (LeavingProductItem $leavingProductItem) {
            return $leavingProductItem->getCreator() && $leavingProductItem->getCreator()->getId() != $this->currentUser->getId();
        })->bindTo($this));

        foreach ($deniedProductItems as $deniedProductItem) {
            foreach ($requestDataJson['productItems'] as $productItem) {
                if (!isset($productItem['id'])) {
                    continue;
                }

                if ($productItem['id'] == $deniedProductItem->getId()) {
                    $deniedProductItemBeforeDeserialize = clone $deniedProductItem;
                    $serializationContext = array_merge($eventData['serializationContext'], ['object_to_populate' => $deniedProductItem]);
                    $this->deserializeService->deserialize(json_encode($productItem), LeavingProductItem::class, 'json', $serializationContext);

                    $context = [
                        'attributes' => [
                            'product' => [
                                'id',
                                'code',
                            ],
                            'price',
                            'quantity',
                            'amount',
                            'measure'
                        ],
                    ];

                    /**
                     * @var LeavingProductItem $deniedProductItemBeforeDeserialize
                     * @var LeavingProductItem $deniedProductItem
                     */
                    $before = $this->serializer->serialize($deniedProductItemBeforeDeserialize, 'json', $context);
                    $after = $this->serializer->serialize($deniedProductItem, 'json', $context);

                    if ($before != $after) {
                        throw new ApiException(
                            'leaving.productItems.editing_access_denied',
                            'ACCESS_DENIED',
                            'productItems',
                            403,
                            ['positionName' => $deniedProductItem->getProduct()->getName(), 'specialistName' => $deniedProductItem->getCreator()->getName()]
                        );
                    }
                }
            }
        }

        $log .= " Write-off of goods from the warehouse: ";

        // Списание товара со склада
        foreach ($requestDataJson['productItems'] as $i => $item) {
            /** @var Product $product */
            $product = $this->entityManager->find("App\Entity\Reference\Product", $item['product']['id']);

            if(isset($item['product']) && isset($item['product']['productStock'])){
                unset($requestDataJson['productItems'][$i]['product']['productStock']);
            }

            if(isset($item['items'])){
                foreach ($item['items'] as $a => $val) {
                    if(isset($val['product']) && isset($val['product']['productStock'])){
                        unset($requestDataJson['productItems'][$i]['items'][$a]['product']['productStock']);
                    }
                }
            }

            if ($product->getPaymentObject()->code != PaymentObjectEnum::COMMODITY) {
                continue;
            }

            /** @var Stock $stock */
            $stock = $this->entityManager->find('App\Entity\Reference\Stock', $item['stock']['id']);

            /** @var ProductStock $productStock */
            $productStock = $this->entityManager->getRepository(ProductStock::class)->findOneBy([
                'product' => $product,
                'stock' => $stock,
            ]);

            if (!$productStock && $product->getPaymentObject()->code !== PaymentObjectEnum::SERVICE) {
                throw new ApiException(sprintf(
                    'Товар %s не найден на складе %s',
                    $product ? $product->getName() : '',
                    $stock ? $stock->getName() : ''
                ));
            }
        }

        $log .= "End. ".json_encode($requestDataJson);

        $event->setData(json_encode($requestDataJson));

        # Logger for KORVET-40
        $this->logger->error($log);

        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function afterFlushLeaving(EventRequest $event)
    {
        /** @var Leaving $leaving */
        $leaving = $event->getData();
        // Подсчет количества приемов, где применяется шаблон
        $appointmentFormTemplate = $leaving->getAppointmentFormTemplate();
        /** @var AppointmentFormTemplate $appointmentFormTemplate */
        foreach ($appointmentFormTemplate as $appointmentFormTemplate) {
            /* @var FormTemplate $formTemplate */
            $formTemplate = $this->entityManager->getRepository(FormTemplate::class)
                ->findOneBy(['id' => $appointmentFormTemplate->getFormTemplate()->getId()]);
            $allAppointmentFormTemplates = $this->entityManager->getRepository(AppointmentFormTemplate::class)
                ->findBy(['formTemplate' => $formTemplate->getId()]);
            $leavingUses = [];
            /* @var AppointmentFormTemplate $item */
            foreach ($allAppointmentFormTemplates as $item) {
                $leavingUses[] = $item->getLeaving()->getId();
            }
            $formTemplate->setLeavingCount(count(array_unique($leavingUses)));
            $this->entityManager->persist($formTemplate);
        }

        /** @var User $user */
        $user = $this->currentUser instanceof User ? $this->currentUser : null;

        /** @var LeavingProductItem $leavingProductItem */
        foreach ($leaving->getProductItems() as $leavingProductItem) {

            if (!empty($leavingProductItem->getItems())) {

                // Привязка товаров к услугам через транзитное поле LeavingProductItem::$items
                /** @var LeavingProductItem $item */
                foreach ($leavingProductItem->getItems() as $item) {
                    if ($item->getProduct()->getPaymentObject()->code !== 'COMMODITY') {
                        throw new ApiException(
                            'leaving.productItems.service_to_service_added',
                            'ERROR_SERVICE_TO_SERVICE_ADDED',
                            'productItems',
                            400
                        );
                    }

                    $item->setParent($leavingProductItem);
                    $item->setLeaving($leaving);
                    $item->setCreator($user);

                    $this->entityManager->persist($item);
                }
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function afterGetLeaving(EventRequest $event)
    {
        /** @var Leaving $leaving */
        $leaving = $event->getData();
        $leaving->setlastUpdate($this->getLastLeavingUpdate($leaving));
    }

    private function getLastLeavingUpdate($leaving) {
        $leavingLogs = $this->entityManager->getRepository(LeavingLogs::class)->findBy(['leaving'=>$leaving], ['date'=> 'ASC']);
        if (isset($leavingLogs) && count($leavingLogs) > 0) {
            return $leavingLogs[count($leavingLogs) - 1]->getDate();
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onAfterProcessAppEntityLeavingLeavingPost' => [
                ['onCreateLeaving', 1],
                ['afterFlushLeaving', 2],
            ],
            'onBeforeProcessAppEntityLeavingLeavingPut' => 'onChangeLeaving',
            'onBeforeProcessAppEntityLeavingLeavingPatch' => 'onChangeLeaving',
            'onAfterProcessAppEntityLeavingLeavingPut' => 'afterFlushLeaving',
            'onAfterProcessAppEntityLeavingLeavingPatch' => 'afterFlushLeaving',
            'onAfterProcessAppEntityLeavingLeavingGetItem' => 'afterGetLeaving',
        ];
    }
}
