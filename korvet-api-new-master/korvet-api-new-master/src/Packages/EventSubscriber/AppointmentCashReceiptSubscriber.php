<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Appointment\Appointment;
use App\Entity\Cash\CashReceipt;
use App\Repository\Cash\CashierScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Exception\ApiException;

class AppointmentCashReceiptSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var  RequestStack */
    private $requestStack;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var CashierScheduleRepository */
    private $cashierScheduleRepository;

    /**
     * AppointmentCashReceiptSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $requestStack
     * @param TokenStorageInterface $tokenStorage
     * @param CashierScheduleRepository $cashierScheduleRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        CashierScheduleRepository $cashierScheduleRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->cashierScheduleRepository = $cashierScheduleRepository;
    }

    /**
     * @param EventRequest $event
     * @throws \Exception
     */
    public function onChangeAppointmentCashReceipt(EventRequest $event)
    {
        /**
         * @var CashReceipt $cashReceipt
         */
        $cashReceipt = $event->getData();

        $currentUser = $this->tokenStorage->getToken()->getUser();
        $scheduleForCashier = $this->cashierScheduleRepository->findCurrentCashierScheduleForCashier($currentUser);
        if (!$scheduleForCashier) {
            throw new ApiException(
                'appointment.cash_receipt.not_found_actual_cashier_shift',
                'ERROR_ACTUAL_CASHIER_SHIFT',
                null,
                400
            );
        }

        $cashReceipt->setType(CashReceiptTypeEnum::getItem(CashReceiptTypeEnum::SELL));
        $cashReceipt->setCashRegister($scheduleForCashier->getCashRegister());

        $this->entityManager->persist($cashReceipt);
        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     */
    public function onCashReceiptUpdate(EventRequest $event)
    {
         /** @var CashReceipt $cashReceipt */
        $cashReceipt = $event->getData();

        /** @var Appointment $appointment */
        $appointment = $this->entityManager->getRepository(Appointment::class)->findOneBy([
            'cashReceipt' => $cashReceipt
        ]);

        // Если у чека сменяется тип оплаты, то он так-же меняется и приеме
        // https://portal.web-slon.ru/company/personal/user/570/tasks/task/view/12126/
        if ($appointment) {
            $appointment->setPaymentType($cashReceipt->getPaymentType());
            $this->entityManager->persist($appointment);
            $this->entityManager->flush();
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onBeforeSaveEntityAppEntityCashCashReceiptPost'
                => 'onChangeAppointmentCashReceipt',
            'onAfterProcessAppEntityCashCashReceiptPut' => 'onCashReceiptUpdate'
        ];
    }
}
