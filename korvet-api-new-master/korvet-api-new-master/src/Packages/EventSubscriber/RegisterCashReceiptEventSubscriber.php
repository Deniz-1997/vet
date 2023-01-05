<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Reference\Appointment\AppointmentStatus;
use App\Repository\Appointment\AppointmentRepository;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Packages\DBAL\Types\PaymentStateEnum;
use App\Packages\Event\RegisterCashReceiptEvent;

class RegisterCashReceiptEventSubscriber implements EventSubscriberInterface
{
    /** @var AppointmentRepository */
    private $appointmentRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * RegisterCashReceiptEventSubscriber constructor.
     * @param AppointmentRepository $appointmentRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AppointmentRepository $appointmentRepository, EntityManagerInterface $entityManager)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            RegisterCashReceiptEvent::NAME => 'onRegisterCashReceiptEvent'
        ];
    }

    /**
     * @param RegisterCashReceiptEvent $registerCashReceiptEvent
     * @throws DBALException
     */
    public function onRegisterCashReceiptEvent(RegisterCashReceiptEvent $registerCashReceiptEvent)
    {
        $appointment = $this->appointmentRepository->findOneBy([
            'cashReceipt' => $registerCashReceiptEvent->getCashReceipt()
        ]);

        if (!$appointment) {
            return;
        }

        // Если чек напечатан, то:
        // 1) статус приема переходит в - ЗАВЕРШЕН
        // 2) статус оплаты в - ОПЛАЧЕН
        $cashReceiptStateCode = $registerCashReceiptEvent->getCashReceipt()->getFiscal()->getState()->code;
        if ($cashReceiptStateCode === FiscalReceiptStateEnum::DONE) {
            /** @var AppointmentStatus $appointmentStatus */
            $appointmentStatus = $this->entityManager->getRepository(AppointmentStatus::class)->findOneBy([
                    'name' => 'Завершен'
                ]);
            $appointment->setStatus($appointmentStatus);
            $appointment->setPaymentState(PaymentStateEnum::getItem(PaymentStateEnum::PAID));
        }

        switch ($registerCashReceiptEvent->getCashReceipt()->getType()->code) {
            case CashReceiptTypeEnum::SELL:
                $appointment->setPaymentState(PaymentStateEnum::getItem(PaymentStateEnum::PAID));
                break;
            case CashReceiptTypeEnum::SELL_RETURN:
                $appointment->setPaymentState(PaymentStateEnum::getItem(PaymentStateEnum::NOT_PAID));
                break;
        }

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();
    }
}
