<?php

namespace App\Packages\DTO\CashierEquipment;

class NomenclatureCode
{
    /** @var ?string */
    public ?string $type;

    /** @var ?string */
    public ?string $gtin;

    /** @var ?string */
    public ?string $serial;

    /**
     * @param string|null $type
     * @return NomenclatureCode
     */
    public function setType(?string $type): NomenclatureCode
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $gtin
     * @return NomenclatureCode
     */
    public function setGtin(?string $gtin): NomenclatureCode
    {
        $this->gtin = $gtin;
        return $this;
    }

    /**
     * @param string|null $serial
     * @return NomenclatureCode
     */
    public function setSerial(?string $serial): NomenclatureCode
    {
        $this->serial = $serial;
        return $this;
    }
}
