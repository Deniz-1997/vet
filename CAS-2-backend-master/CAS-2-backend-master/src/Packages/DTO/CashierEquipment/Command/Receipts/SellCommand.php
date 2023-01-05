<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class SellCommand extends BaseRegisterCashReceiptCommand
{
    /** @var string */
    public string $type = 'sell';
}
