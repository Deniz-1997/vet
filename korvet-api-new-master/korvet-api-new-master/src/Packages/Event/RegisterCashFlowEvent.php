<?php

namespace App\Packages\Event;

use App\Entity\Cash\CashFlow;
use Symfony\Contracts\EventDispatcher\Event;
use App\Packages\Handler\CashInHandler;

class RegisterCashFlowEvent extends Event
{
    const NAME = 'register.cash.flow';

    /** @var CashFlow */
    private $cashFlow;

    /**
     * RegisterCashFlowEvent constructor.
     * @param CashFlow $cashFlow
     */
    public function __construct(CashFlow $cashFlow)
    {
        $this->cashFlow = $cashFlow;
    }

    /**
     * @return CashFlow
     */
    public function getCashFlow(): CashFlow
    {
        return $this->cashFlow;
    }
}
