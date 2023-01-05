<?php


namespace App\Packages\DTO\CashierEquipment;


class Payment
{
    /** @var string */
    public string $type;

    /** @var float */
    public float $sum;

    /**
     * @param string $type
     * @return Payment
     */
    public function setType(string $type): Payment
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param float $sum
     * @return Payment
     */
    public function setSum(float $sum): Payment
    {
        $this->sum = $sum;
        return $this;
    }
}
