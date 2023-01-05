<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

use App\Packages\DTO\CashierEquipment\ClientInfo;
use App\Packages\DTO\CashierEquipment\Command\BaseCommandWithOperator;
use App\Packages\DTO\CashierEquipment\Item;
use App\Packages\DTO\CashierEquipment\Operator;
use App\Packages\DTO\CashierEquipment\Payment;

abstract class BaseRegisterCashReceiptCommand extends BaseCommandWithOperator
{
    /** @var string */
    public string $taxationType;

    /** @var bool */
    public bool $electronically;

    /** @var bool */
    public bool $ignoreNonFiscalPrintErrors;

    /** @var Operator */
    public Operator $operator;

    /** @var ClientInfo */
    public ClientInfo $clientInfo;

    /** @var Item[] */
    public array $items = [];

    /** @var Payment[] */
    public array $payments = [];

    /** @var int */
    public int $cashReceiptId;

    /**
     * BaseRegisterCashReceiptCommand constructor.
     *
     * @param int $cashReceiptId
     * @param string $cashRegisterId
     * @param Operator $operator
     */
    public function __construct(int $cashReceiptId, string $cashRegisterId, Operator $operator)
    {
        $this->cashReceiptId = $cashReceiptId;

        parent::__construct($cashRegisterId, $operator);
    }

    /**
     * @param string $taxationType
     * @return BaseRegisterCashReceiptCommand
     */
    public function setTaxationType(string $taxationType): BaseRegisterCashReceiptCommand
    {
        $this->taxationType = $taxationType;
        return $this;
    }

    /**
     * @param bool $electronically
     * @return BaseRegisterCashReceiptCommand
     */
    public function setElectronically(bool $electronically): BaseRegisterCashReceiptCommand
    {
        $this->electronically = $electronically;
        return $this;
    }

    /**
     * @param bool $ignoreNonFiscalPrintErrors
     * @return BaseRegisterCashReceiptCommand
     */
    public function setIgnoreNonFiscalPrintErrors(bool $ignoreNonFiscalPrintErrors): BaseRegisterCashReceiptCommand
    {
        $this->ignoreNonFiscalPrintErrors = $ignoreNonFiscalPrintErrors;
        return $this;
    }

    /**
     * @param Operator $operator
     * @return BaseRegisterCashReceiptCommand
     */
    public function setOperator(Operator $operator): BaseRegisterCashReceiptCommand
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @param ClientInfo $clientInfo
     * @return BaseRegisterCashReceiptCommand
     */
    public function setClientInfo(ClientInfo $clientInfo): BaseRegisterCashReceiptCommand
    {
        $this->clientInfo = $clientInfo;
        return $this;
    }

    /**
     * @param Item $item
     * @return BaseRegisterCashReceiptCommand
     */
    public function addItem(Item $item): BaseRegisterCashReceiptCommand
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @param Payment $payment
     * @return BaseRegisterCashReceiptCommand
     */
    public function addPayment(Payment $payment): BaseRegisterCashReceiptCommand
    {
        $this->payments[] = $payment;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        foreach ($array['items'] as &$item) {
            if (isset($item['nomenclatureCode']) && isset($item['nomenclatureCode']['type']) && $item['nomenclatureCode']['type'] === '') {
                $item['nomenclatureCode']['type'] = null;
            }
        }

        return $array;
    }
}
