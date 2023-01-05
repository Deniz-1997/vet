<?php

namespace App\Packages\DTO\CashierEquipment;

class ClientInfo
{
    /** @var string */
    public string $emailOrPhone;

    /**
     * ClientInfo constructor.
     * @param string $emailOrPhone
     */
    public function __construct(string $emailOrPhone)
    {
        $this->emailOrPhone = $emailOrPhone;
    }
}
