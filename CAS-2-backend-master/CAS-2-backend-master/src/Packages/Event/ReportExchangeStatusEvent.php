<?php

namespace App\Packages\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Reference\CashRegister;

class ReportExchangeStatusEvent extends Event
{
    const NAME = 'report.exchange.status';

    /** @var cashRegister */
    private $cashRegister;

    /**
     * ReportExchangeStatusEvent constructor.
     * @param CashRegister $cashRegister
     */
    public function __construct(CashRegister $cashRegister)
    {
        $this->cashRegister = $cashRegister;
    }

    /**
     * @return CashRegister
     */
    public function getCashRegister(): CashRegister
    {
        return $this->cashRegister;
    }
}
