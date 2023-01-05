<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Cash\CashReceipt;
use App\Interfaces\CashierUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\Cash\CashReceiptChanges;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use DateTime;

class CashReceiptSubscriber implements EventSubscriberInterface
{
    /** @var CashierUserInterface|null */
    private $currentUser;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $token = $tokenStorage->getToken();
        $this->currentUser = $token ? $token->getUser() : null;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'onBeforeSaveEntityAppEntityCashCashReceiptPost' => 'postOnBeforeSave',
            'onBeforeSaveEntityAppEntityCashCashReceiptPatch' => 'patchOnBeforeSave',
            'onBeforeSaveEntityAppEntityCashCashReceiptPut' => 'putOnBeforeSave',
            'custom.event.register_cash_receipt.event' => 'onRegisterCashReceiptEvent',
            'onAfterProcessAppEntityCashCashReceiptDelete'  => 'onChangeCashReceipt',
        );
    }

    /**
     * @param EventRequest $event
     * @throws \Doctrine\DBAL\Exception
     */
    public function postOnBeforeSave(EventRequest $event)
    {
        $this->onBeforeSave($event);

        /** @var CashReceipt $entity */
        $entity = $event->getData();

        // при создании чека надо заполнить Автора
        $entity->setCreator($this->currentUser);

        $fiscal = $entity->getFiscal() ?? new FiscalParameters();
        $fiscal->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));
        $entity->setFiscal($fiscal);
    }

    /**
     * @param EventRequest $event
     */
    public function patchOnBeforeSave(EventRequest $event)
    {
        $this->onBeforeSave($event);
    }

    /**
     * @param EventRequest $event
     */
    public function putOnBeforeSave(EventRequest $event)
    {
        $this->onBeforeSave($event);
    }

    /**
     * @param EventRequest $event
     */
    public function onBeforeSave(EventRequest $event)
    {
        /** @var CashReceipt $entity */
        $entity = $event->getData();

        // проставляем систему налогообложения из организации ККМ
        $entity->setTaxationType($entity->getCashRegister()->getOrganization()->getTaxationType());
    }

    /**
     * @param EventRequest $event
     */
    public function onChangeCashReceipt(EventRequest $event)
    {
        /** @var CashReceipt $entity */
        $entity = $event->getData();

        if (isset($entity) && $entity->isDeleted() == true && isset($this->currentUser)) {
            /** @var CashReceiptChanges $changes */
            $changes = $this->entityManager->getRepository(CashReceiptChanges::class)
                ->findOneBy([
                    'user' => $this->currentUser,
                    'сashReceipt' => $entity,
                    'createdAt' => new DateTime()
                ]);
            if (!isset($changes)) {
                $changes = new CashReceiptChanges();
            }
            $changes->setUser($this->currentUser);
            $changes->setCashReceipt($entity);
            $changes->setType('DELETE');
            $changes->setCreatedAt(new DateTime());

            $this->entityManager->persist($changes);
            $this->entityManager->flush();
        }
    }
}
