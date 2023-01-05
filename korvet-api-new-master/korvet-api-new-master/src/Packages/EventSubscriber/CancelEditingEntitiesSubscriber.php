<?php


namespace App\Packages\EventSubscriber;

use App\Entity\Cash\CashReceipt;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Shift;
use App\Exception\ApiException;

class CancelEditingEntitiesSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'onBeforeSaveEntityAppEntityCashCashReceiptPatch' => 'onBeforeUpdate',
            'onBeforeSaveEntityAppEntityCashCashReceiptPut' => 'onBeforeUpdate',
            'onBeforeSaveEntityAppEntityShiftPatch' => 'onBeforeUpdate',
            'onBeforeSaveEntityAppEntityShiftPut' => 'onBeforeUpdate',
        );
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function onBeforeUpdate(EventRequest $event)
    {
        $entity = $event->getData();

        // запрещаем менять пробитые чеки и закрытые смены
        if ($entity instanceof Shift || $entity instanceof CashReceipt) {
            $cancel = false;
            $fiscal = null;

            if ($entity instanceof Shift) {
                $fiscal = $entity->getFiscalClose() ?? $entity->getFiscalClose();
            }

            if ($entity instanceof CashReceipt) {
                $fiscal = $entity->getFiscal();
            }

            if ($fiscal && $fiscal->getState() && $fiscal->getState()->code !== FiscalReceiptStateEnum::NEW) {
                $cancel = true;
            }

            if ($cancel) {
                $entityCode = $entity instanceof Shift ? 'shift' : 'cash_receipt';
                throw new ApiException('cashier.'.$entityCode.'.access_denied_update_entity', 'FOUND_ALREADY_OPENED_SHIFT', '', 400);
            }
        }
    }
}
