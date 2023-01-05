<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

class BuyCorrectionCommand extends BaseRegisterCorrectionCommand
{
    /** @var string */
    public string $type = 'buyCorrection';
}
