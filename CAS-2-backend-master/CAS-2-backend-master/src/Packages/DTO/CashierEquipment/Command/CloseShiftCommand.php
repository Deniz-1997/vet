<?php

namespace App\Packages\DTO\CashierEquipment\Command;

use App\Packages\DTO\CashierEquipment\Operator;

class CloseShiftCommand extends BaseCommandWithOperator
{
    public string $type = 'closeShift';
}
