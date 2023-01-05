<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class SellCorrectionCommand extends BaseRegisterCorrectionCommand
{
    /** @var string */
    public string $type = 'sellCorrection';
}
