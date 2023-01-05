<?php

namespace App\Packages\EventSubscriber;

use App\Packages\DBAL\Types\ShiftStateEnum;
use App\Entity\Reference\CashRegister;
use App\Entity\Shift;
use App\Interfaces\CashierUserInterface;
use App\Repository\ShiftRepository;
use Doctrine\DBAL\Exception as DBALException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Exception\ApiException;
use Doctrine\ORM\EntityManagerInterface;

class ShiftSubscriber implements EventSubscriberInterface
{
    /** @var CashierUserInterface|null */
    private $currentUser;

    /** @var EntityManagerInterface*/
    private $entityManager;

    /**
     * ShiftSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $token = $tokenStorage->getToken();
        $this->currentUser = $token ? $token->getUser() : null;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'onBeforeSaveEntityAppEntityShiftPost' => 'postOnBeforeSave',
            'onBeforeSaveEntityAppEntityShiftPatch' => 'preUpdateShift',
            'onBeforeSaveEntityAppEntityShiftPut' => 'preUpdateShift',
            'onBeforeProcessAppEntityShiftDelete' => 'preDeleteShift'
        );
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     * @throws DBALException
     */
    public function postOnBeforeSave(EventRequest $event)
    {
        /** @var Shift $entity*/
        $entity = $event->getData();

        $cashRegister = $entity->getCashRegister();

        /** @var ShiftRepository $shiftRepository*/
        $shiftRepository = $this->entityManager->getRepository(Shift::class);

        /** @var Shift $shift*/
        $shift = $shiftRepository->findLastShift($cashRegister);

        if (is_object($shift)) {
            if ($shift->getState()->code === ShiftStateEnum::OPEN) {
                throw new ApiException('cashier.shift.found_open_shift_for_selected_cash_register', 'FOUND_ALREADY_OPEN_SHIFT_FOR_SELECTED_CASH_REGISTER', '', 400);
            }
        }

        $shift = $shiftRepository->findOneBy(
            ['cashier' => $this->currentUser->getId(), 'deleted' => false],
            ['id' => 'DESC']
        );

        if (is_object($shift)) {
            if ($shift->getState()->code === ShiftStateEnum::OPEN) {
                throw new ApiException('cashier.shift.found_open_shift_for_selected_cashier', 'FOUND_ALREADY_OPEN_SHIFT_FOR_SELECTED_CASHIER', '', 400);
            }
        }

    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     * @throws DBALException
     */
    public function preUpdateShift(EventRequest $event)
    {
        /** @var Shift $shift */
        $shift = $event->getData();

        if ($shift->getState()->code === ShiftStateEnum::CLOSE) {
            throw new ApiException('cashier.shift.update_closed_shift_error', 'CASHIER_UPDATE_CLOSED_SHIFT', null, 400);
        }
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     * @throws DBALException
     */
    public function preDeleteShift(EventRequest $event)
    {
        $entity = $event->getData();

        /** @var ShiftRepository $shiftRepository*/
        $shiftRepository = $this->entityManager->getRepository(Shift::class);

        /** @var Shift $shift */
        $shift = $shiftRepository->findOneBy(['id' => $entity['id']]);

        if ($shift->getState()->code === ShiftStateEnum::OPEN) {
            throw new ApiException('cashier.shift.delete_opened_shift_error', 'DELETE_OPENED_SHIFT', null, 400);

        }
    }
}
