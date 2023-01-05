<?php

namespace App\Packages\DTO\CashierEquipment\Command\Receipts;

abstract class BaseRegisterCorrectionCommand extends BaseRegisterCashReceiptCommand
{
    /** @var string */
    public string $correctionType;

    /** @var string */
    public string $correctionBaseDate;

    /** @var string */
    public string $correctionBaseNumber;

    /** @var string */
    public string $correctionBaseName;

    /**
     * @param string $correctionType
     * @return BaseRegisterCorrectionCommand
     */
    public function setCorrectionType(string $correctionType): BaseRegisterCorrectionCommand
    {
        $this->correctionType = $correctionType;
        return $this;
    }

    /**
     * @param string $correctionBaseDate
     * @return BaseRegisterCorrectionCommand
     */
    public function setCorrectionBaseDate(string $correctionBaseDate): BaseRegisterCorrectionCommand
    {
        $this->correctionBaseDate = $correctionBaseDate;
        return $this;
    }

    /**
     * @param string $correctionBaseNumber
     * @return BaseRegisterCorrectionCommand
     */
    public function setCorrectionBaseNumber(string $correctionBaseNumber): BaseRegisterCorrectionCommand
    {
        $this->correctionBaseNumber = $correctionBaseNumber;
        return $this;
    }

    /**
     * @param string $correctionBaseName
     * @return BaseRegisterCorrectionCommand
     */
    public function setCorrectionBaseName(string $correctionBaseName): BaseRegisterCorrectionCommand
    {
        $this->correctionBaseName = $correctionBaseName;
        return $this;
    }
}
