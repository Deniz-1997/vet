<?php

namespace App\Packages\DTO\CashierEquipment;

class Item
{
    /** @var string */
    public string $type;

    /** @var string */
    public string $name;

    /** @var float */
    public float $price;

    /** @var float */
    public float $quantity;

    /** @var float */
    public float $amount;

    // /** @var float */
    // public $infoDiscountAmount = 0;

    // /** @var int */
    // public $department = 1;

    /** @var string */
    public string $measurementUnit;

    /** @var string */
    public string $paymentMethod = 'fullPrepayment';

    /** @var string */
    public string $paymentObject;

    /** @var NomenclatureCode */
    public NomenclatureCode $nomenclatureCode;

    /** @var Tax */
    public Tax $tax;

    // /** @var AgentInfo */
    // public $agentInfo;

    // /** @var SupplierInfo */
    // public $supplierInfo;
}
