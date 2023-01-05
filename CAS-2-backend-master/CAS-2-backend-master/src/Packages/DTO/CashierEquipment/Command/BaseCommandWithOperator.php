<?php

namespace App\Packages\DTO\CashierEquipment\Command;

use App\Packages\DTO\CashierEquipment\Operator;

abstract class BaseCommandWithOperator extends BaseCommand
{
    /** @var Operator */
    public Operator $operator;

    /**
     * BaseCommandWithOperator constructor.
     * @param string $cashRegisterId
     * @param Operator $operator
     */
    public function __construct(string $cashRegisterId, Operator $operator)
    {
        parent::__construct($cashRegisterId);
        $this->operator = $operator;
    }


}
