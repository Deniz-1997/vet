<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class BuyReturnCommand extends BaseRegisterCashReceiptCommand
{
    /** @var string */
    public string $type = 'buyReturn';
}
