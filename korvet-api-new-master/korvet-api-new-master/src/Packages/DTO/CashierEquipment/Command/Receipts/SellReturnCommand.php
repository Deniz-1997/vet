<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class SellReturnCommand extends BaseRegisterCashReceiptCommand
{
    /** @var string */
    public string $type = 'sellReturn';
}
