<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class BuyCommand extends BaseRegisterCashReceiptCommand
{
    /** @var string */
    public string $type = 'buy';
}
