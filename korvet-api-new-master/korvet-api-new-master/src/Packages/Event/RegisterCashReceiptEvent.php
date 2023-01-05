<?php

namespace App\Packages\Event;

use App\Entity\Cash\CashReceipt;
use Symfony\Contracts\EventDispatcher\Event;
use App\Packages\Handler\RegisterCashReceiptHandler;

class RegisterCashReceiptEvent extends Event
{
    const NAME = 'register.cash.receipt';

    /** @var CashReceipt */
    private $cashReceipt;

    /**
     * RegisterCashReceiptEvent constructor.
     * @param CashReceipt $cashReceipt
     */
    public function __construct(CashReceipt $cashReceipt)
    {
        $this->cashReceipt = $cashReceipt;
    }

    /**
     * @return CashReceipt
     */
    public function getCashReceipt(): CashReceipt
    {
        return $this->cashReceipt;
    }
}
