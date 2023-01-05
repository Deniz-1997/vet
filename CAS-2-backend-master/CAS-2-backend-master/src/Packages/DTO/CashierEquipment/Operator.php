<?php


namespace App\Packages\DTO\CashierEquipment;


class Operator
{
    /** @var string */
    public string $name;

    /** @var string|null */
    public ?string $vatin;

    /**
     * Operator constructor.
     * @param string $name
     * @param string|null $vatin
     */
    public function __construct(string $name, ?string $vatin)
    {
        $this->name = $name;
        $this->vatin = $vatin;
    }
}
