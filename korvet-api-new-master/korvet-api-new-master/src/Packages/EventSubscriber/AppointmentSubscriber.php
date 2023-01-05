<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Appointment\Appointment;
use App\Entity\Appointment\AppointmentFormTemplate;
use App\Entity\Appointment\AppointmentLogs;
use App\Entity\ProductStock;
use App\Entity\Reference\Appointment\AppointmentProductItem;
use App\Entity\Reference\FormTemplate;
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

class AppointmentSubscriber implements EventSubscriberInterface
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
     * AppointmentSubscriber constructor.
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
    public function onCreateAppointment(EventRequest $event)
    {

        /** @var Appointment $appointment */
        $appointment = $event->getData();
        /** @var User $user */
        $user = $this->currentUser instanceof User ? $this->currentUser : null;

        $log = 'Data: '.json_encode($appointment).'; Change appointment ID: ' . $appointment->getId() . ' ';

        if ($appointment->getUser()) {
            // устанавливаем клинику из специалиста
            $appointment->setUnit($appointment->getUser()->getUnit());
        } elseif ($this->currentUser instanceof User) {
            // устанавливаем клинику из текущего пользователя
            $appointment->setUnit($this->currentUser->getUnit());
        }

        $log .= " Write-off of goods from the warehouse: ";

        /** @var AppointmentProductItem $appointmentProductItem */
        foreach ($appointment->getProductItems() as $appointmentProductItem) {
            $appointmentProductItem->setCreator($user);

            // списание товара со склада
            /** @var Product $product */
            $product = $appointmentProductItem->getProduct();
            /** @var Stock $stock */
            $stock = $appointmentProductItem->getStock();
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

            $quantity = $productStock->getQuantity() - $appointmentProductItem->getQuantity();

            $log .= " Stock: " . $stock->getName() . "; Product: " . $product->getId() . "; Product Stock Quantity: " . $productStock->getQuantity() . "; 
            Appointment Product Item Quantity: " . $appointmentProductItem->getQuantity() . "; Quantity: $quantity";

            $productStock->setQuantity($quantity);
            $this->entityManager->persist($productStock);
        }

        $log .= " End.";

        # Logger for KORVET-40
        $this->logger->error($log);

        $appointmentLogs = new AppointmentLogs();
        $appointmentLogs->setAppointment($appointment);
        $appointmentLogs->setStatus($appointment->getStatus());
        $appointmentLogs->setUser($user);
        $appointmentLogs->setDate(new \DateTime());
        $this->entityManager->persist($appointmentLogs);

        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function onChangeAppointment(EventRequest $event)
    {
        $eventData = $event->getData();

        $requestDataJson = json_decode($eventData['content'], true);
        /** @var Appointment $appointment */
        $appointment = $this->entityManager->find($eventData['objectName'], $eventData['id']);
        //Проверка последнего сохранения
         /** @var Appointment $appointment */
         $appointment = $this->entityManager->find($eventData['objectName'], $eventData['id']);
         //Проверка последнего сохранения
         $currentLastUpdate =  isset($requestDataJson['lastUpdate']) ? new \DateTime($requestDataJson['lastUpdate']) : null;
         $lastAppointmentUpdate =  $this->getLastAppointmentUpdate($appointment);
         if ($lastAppointmentUpdate !== null && $currentLastUpdate!==null && $lastAppointmentUpdate != $currentLastUpdate) {
             throw new ApiException('Прием уже был отредактирован, обновите страницу перед сохранением','APPOINTMENT_CHANGED',null, 400);
         }
        
        if (isset($requestDataJson['appointmentFormTemplate'])) {
            foreach ($requestDataJson['appointmentFormTemplate'] as $formTemplateKey => $appointmentTemplate) {
                /** @var FormTemplate $template */
                $template = $this->entityManager->getRepository(FormTemplate::class)->findOneBy(['id' => $appointmentTemplate['formTemplate']['id']]);
                if ($template->isDeleted()) {
                    throw new ApiException(
                        'formTemplate.attempt_to_use_deleted_formTemplate_in_appointment',
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

       


        $appointmentLogs = new AppointmentLogs();
        $appointmentLogs->setAppointment($appointment);
        $appointmentLogs->setStatus($appointment->getStatus());
        $appointmentLogs->setUser($this->currentUser);
        $appointmentLogs->setDate(new \DateTime());
        $this->entityManager->persist($appointmentLogs);

        $log = 'Change appointment in subscriber ID: ' . $eventData['id'] . ' ';

        // Если из приема удалили шаблон, уменьшаем счетчик использований на 1
        /** @var AppointmentFormTemplate $appointmentFormTemplate */
        foreach ($appointment->getAppointmentFormTemplate() as $appointmentFormTemplate) {
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
                $formTemplate->setAppointmentCount($formTemplate->getAppointmentCount() - 1);
                $this->entityManager->persist($formTemplate);
            }
        }

        //Если номенклатура не меняется - ничего не делаем
        if (!isset($requestDataJson['productItems'])) {
            return;
        }

        // Если чек распечатан и прием оплачен, то номенклатуры и данные об оплате не изменяются.
        if ($appointment->getPaymentState()->code === PaymentStateEnum::PAID
            && $appointment->getCashReceipt()
            && $appointment->getCashReceipt()->getFiscal()->getState() === FiscalReceiptStateEnum::DONE
        ) {
        // TODO KRVT-12126 https://portal.web-slon.ru/company/personal/user/570/tasks/task/view/12126/
        // Доработать исключение возможности изменять номенклатуры и данные об оплате
            return;
        }

        if ($appointment->getState()->code === DocumentStateEnum::REGISTERED &&
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
        /** @var AppointmentProductItem $item */
        foreach ($appointment->getProductItems() as $item) {
            if ($item->getParent()) {
                $log .= "  " . $item->getId() . " ";
                $this->entityManager->remove($item);
                $this->entityManager->flush();
            }
        }

        $log .= ";";

        $deniedProductItems = $appointment->getProductItems()->filter((function (AppointmentProductItem $appointmentProductItem) {
            return $appointmentProductItem->getCreator() && $appointmentProductItem->getCreator()->getId() != $this->currentUser->getId();
        })->bindTo($this));

        foreach ($deniedProductItems as $deniedProductItem) {
            foreach ($requestDataJson['productItems'] as $productItem) {
                if (!isset($productItem['id'])) {
                    continue;
                }

                if ($productItem['id'] == $deniedProductItem->getId()) {
                    $deniedProductItemBeforeDeserialize = clone $deniedProductItem;
                    $serializationContext = array_merge($eventData['serializationContext'], ['object_to_populate' => $deniedProductItem]);
                    $this->deserializeService->deserialize(json_encode($productItem), AppointmentProductItem::class, 'json', $serializationContext);

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
                     * @var AppointmentProductItem $deniedProductItemBeforeDeserialize
                     * @var AppointmentProductItem $deniedProductItem
                     */
                    $before = $this->serializer->serialize($deniedProductItemBeforeDeserialize, 'json', $context);
                    $after = $this->serializer->serialize($deniedProductItem, 'json', $context);

                    if ($before != $after) {
                        throw new ApiException(
                            'appointment.productItems.editing_access_denied',
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
    public function afterFlushAppointment(EventRequest $event)
    {
        /** @var Appointment $appointment */
        $appointment = $event->getData();
        // Подсчет количества приемов, где применяется шаблон
        $appointmentFormTemplates = $appointment->getAppointmentFormTemplate();
        /** @var AppointmentFormTemplate $appointmentFormTemplate */
        foreach ($appointmentFormTemplates as $appointmentFormTemplate) {
            /* @var FormTemplate $formTemplate */
            $formTemplate = $this->entityManager->getRepository(FormTemplate::class)
                ->findOneBy(['id' => $appointmentFormTemplate->getFormTemplate()->getId()]);
            $allAppointmentFormTemplates = $this->entityManager->getRepository(AppointmentFormTemplate::class)
                ->findBy(['formTemplate' => $formTemplate->getId()]);
            $appointmentsUses = [];
            /* @var AppointmentFormTemplate $item */
            foreach ($allAppointmentFormTemplates as $item) {
                $appointmentsUses[] = $item->getAppointment()->getId();
            }
            $formTemplate->setAppointmentCount(count(array_unique($appointmentsUses)));
            $this->entityManager->persist($formTemplate);
        }

        /** @var User $user */
        $user = $this->currentUser instanceof User ? $this->currentUser : null;

        /** @var AppointmentProductItem $appointmentProductItem */
        foreach ($appointment->getProductItems() as $appointmentProductItem) {

            if (!empty($appointmentProductItem->getItems())) {

                // Привязка товаров к услугам через транзитное поле AppointmentProductItem::$items
                /** @var AppointmentProductItem $item */
                foreach ($appointmentProductItem->getItems() as $item) {
                    if ($item->getProduct()->getPaymentObject()->code !== 'COMMODITY') {
                        throw new ApiException(
                            'appointment.productItems.service_to_service_added',
                            'ERROR_SERVICE_TO_SERVICE_ADDED',
                            'productItems',
                            400
                        );
                    }

                    $item->setParent($appointmentProductItem);
                    $item->setAppointment($appointment);
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
    public function afterGetAppointment(EventRequest $event)
    {
        /** @var Appointment $appointment */
        $appointment = $event->getData();
        $appointment->setlastUpdate($this->getLastAppointmentUpdate($appointment));
    }

    private function getLastAppointmentUpdate($appointment) {
        $appointmentLogs = $this->entityManager->getRepository(AppointmentLogs::class)->findBy(['appointment'=>$appointment], ['date'=> 'ASC']);
        if (isset($appointmentLogs) && count($appointmentLogs) > 0) {
            return $appointmentLogs[count($appointmentLogs) - 1]->getDate();
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onAfterProcessAppEntityAppointmentAppointmentPost' => [
                ['onCreateAppointment', 1],
                ['afterFlushAppointment', 2],
            ],
            'onBeforeProcessAppEntityAppointmentAppointmentPut' => 'onChangeAppointment',
            'onBeforeProcessAppEntityAppointmentAppointmentPatch' => 'onChangeAppointment',
            'onAfterProcessAppEntityAppointmentAppointmentPut' => 'afterFlushAppointment',
            'onAfterProcessAppEntityAppointmentAppointmentPatch' => 'afterFlushAppointment',
            'onAfterProcessAppEntityAppointmentAppointmentGetitem' => 'afterGetAppointment',
        ];
    }
}
