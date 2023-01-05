<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class BuyReturnCorrectionCommand extends BaseRegisterCorrectionCommand
{
    /** @var string */
    public string $type = 'buyReturnCorrection';
}
