<?php

namespace App\Packages\DTO\CashierEquipment\Command;

/**
 * Class ReportOfExchangeStatusCommand
 * @package App\Packages\DTO\CashierEquipment\Command
 */
class ReportOfExchangeStatusCommand extends BaseCommandWithOperator
{
    public string $type = 'reportOfdExchangeStatus';
}
