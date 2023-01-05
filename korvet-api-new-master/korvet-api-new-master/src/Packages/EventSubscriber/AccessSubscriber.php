<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Cash\CashFlow;
use App\Entity\Cash\CashReceipt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\Reference\CashRegister;
use App\Entity\Shift;
use App\Packages\Security\Voter\CashRegisterVoter;
use App\Exception\ApiException;

class AccessSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private $authorizationChecker;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AccessSubscriber constructor.
     * @param Security $authorizationChecker
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Security $authorizationChecker, EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'onBeforeSaveEntityAppEntityCashCashReceiptPost' => 'checkAccess',
            'onBeforeSaveEntityAppEntityCashCashReceiptPatch' => 'checkAccess',
            'onBeforeSaveEntityAppEntityCashCashReceiptPut' => 'checkAccess',

            'onBeforeSaveEntityAppEntityShiftPost' => 'checkAccess',
            'onBeforeSaveEntityAppEntityShiftPatch' => 'checkAccess',
            'onBeforeSaveEntityAppEntityShiftPut' => 'checkAccess',

            'onBeforeSaveEntityAppEntityCashCashFlowPost' => 'checkAccess',
            'onBeforeSaveEntityAppEntityCashCashFlowPatch' => 'checkAccess',
            'onBeforeSaveEntityAppEntityCashCashFlowPut' => 'checkAccess',

            'onBeforeProcessAppEntityShiftGetitem' => 'checkAccessGetItem',
            'onBeforeProcessAppEntityCashCashFlowGetitem' => 'checkAccessGetItem',
            'onBeforeProcessAppEntityCashCashReceiptGetitem' => 'checkAccessGetItem'
        );
    }

    /**
     * @param EventRequest $eventRequest
     * @throws ApiException
     */
    public function checkAccess(EventRequest $eventRequest)
    {
        /** @var Shift|CashReceipt|CashFlow $entity */
        $entity = $eventRequest->getData();

        if (!$cashRegister = $entity->getCashRegister()) {
            return;
        }

        if (!$this->authorizationChecker->isGranted(CashRegisterVoter::ATTRIBUTE_AVAILABLE_CASH_REGISTER, $cashRegister)) {
            throw new ApiException('cashier.cash_register.access_denied', 'ACCESS_DENIED', null, 403);
        }
    }

    /**
     * @param EventRequest $eventRequest
     * @throws ApiException
     */
    public function checkAccessGetItem(EventRequest $eventRequest)
    {
        $data = $eventRequest->getData();

        $entity = $this->entityManager->getRepository($data['object'])->find($data['id']);
        if (!$entity) {
            return;
        }

        $cashRegister = $entity->getCashRegister();

        if (!$this->authorizationChecker->isGranted(CashRegisterVoter::ATTRIBUTE_AVAILABLE_CASH_REGISTER, $cashRegister)) {
            throw new ApiException('cashier.cash_register.access_denied', 'ACCESS_DENIED', null, 403);
        }
    }
}
