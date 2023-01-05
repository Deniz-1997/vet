<?php


namespace App\Packages\EventSubscriber;

use App\Entity\Cash\CashFlow;
use Doctrine\DBAL\Exception as DBALException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Embeddable\FiscalParameters;

/**
 * Class CashFlowSubscriber
 */
class CashFlowSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'onBeforeSaveEntityAppEntityCashCashFlowPost' => 'postOnBeforeSave'
        ];
    }

    /**
     * @param EventRequest $eventRequest
     * @throws DBALException
     */
    public function postOnBeforeSave(EventRequest $eventRequest)
    {
        /** @var CashFlow $cashFlow */
        $cashFlow = $eventRequest->getData();

        $fiscal = $cashFlow->getFiscal() ?? new FiscalParameters();
        $fiscal->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));

        $cashFlow->setFiscal($fiscal);
    }
}
