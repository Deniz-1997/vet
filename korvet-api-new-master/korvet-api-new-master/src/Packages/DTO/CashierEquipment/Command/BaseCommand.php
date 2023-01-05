<?php

namespace App\Packages\DTO\CashierEquipment\Command;

abstract class BaseCommand
{
    /** @var string */
    public string $cashRegisterId;

    /** @var string */
    public string $type;

    /**
     * BaseCommand constructor.
     * @param string $cashRegisterId
     */
    public function __construct(string $cashRegisterId)
    {
        $this->cashRegisterId = $cashRegisterId;
    }

    function __toString()
    {
        return json_encode($this);
    }

    function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
