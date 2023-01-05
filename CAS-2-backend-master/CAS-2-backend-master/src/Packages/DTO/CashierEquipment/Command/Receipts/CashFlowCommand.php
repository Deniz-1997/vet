<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

use App\Packages\DTO\CashierEquipment\Command\BaseCommandWithOperator;
use App\Packages\DTO\CashierEquipment\Operator;

/**
 * Class CashFlowCommand
 */
class CashFlowCommand extends BaseCommandWithOperator
{
    /** @var string */
    public string $type;

    /** @var float */
    public float $cashSum;

    /** @var int */
    public int $cashReceiptId;

    /**
     * CashFlowCommand constructor.
     * @param string $cashRegisterId
     * @param int $cashReceiptId
     * @param Operator $operator
     */
    public function __construct(string $cashRegisterId, int $cashReceiptId, Operator $operator )
    {
        $this->cashReceiptId = $cashReceiptId;
        parent::__construct($cashRegisterId, $operator);
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        switch ($type) {
            case 'CASH_IN':
                $this->type = 'cashIn';
                break;
            case 'CASH_OUT':
                $this->type = 'cashOut';
                break;
        }
    }

    /**
     * @param float $cashSum
     */
    public function setCashSum(float $cashSum): void
    {
        $this->cashSum = $cashSum;
    }
}
