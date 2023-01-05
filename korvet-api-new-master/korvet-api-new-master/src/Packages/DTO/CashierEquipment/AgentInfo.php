<?php

namespace App\Packages\DTO\CashierEquipment;

class AgentInfo
{
    /** @var string[] */
    public array $agents;

    /** @var PayingAgent */
    public PayingAgent $payingAgent;

    /** @var ReceivePaymentsOperator */
    public ReceivePaymentsOperator $receivePaymentsOperator;

    /** @var MoneyTransferOperator */
    protected $moneyTransferOperator;
}
