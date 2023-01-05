<?php

namespace App\Interfaces;

use App\Packages\DBAL\Types\TaxationTypeEnum;

/**
 * Interface OrganizationInterface
 * @package App\Interfaces
 */
interface OrganizationInterface
{
    /**
     * @return string
     */
    public function getInn(): string;

    /**
     * @param string $inn
     * @return static
     */
    public function setInn(string $inn): OrganizationInterface;

    /**
     * @return TaxationTypeEnum
     */
    public function getTaxationType(): TaxationTypeEnum;

    /**
     * @param TaxationTypeEnum $taxationType
     * @return static
     */
    public function setTaxationType(TaxationTypeEnum $taxationType): OrganizationInterface;
}
