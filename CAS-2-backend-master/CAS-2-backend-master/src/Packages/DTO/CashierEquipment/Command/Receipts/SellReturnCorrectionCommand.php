<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class SellReturnCorrectionCommand extends BaseRegisterCorrectionCommand
{
    /** @var string */
    public string $type = 'sellReturnCorrection';
}
