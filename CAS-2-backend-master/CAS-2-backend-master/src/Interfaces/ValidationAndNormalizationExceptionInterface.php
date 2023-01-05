<?php


namespace App\Interfaces;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Interface ValidationAndNormalizationExceptionInterface
 */
interface ValidationAndNormalizationExceptionInterface
{
    /**
     * @return ConstraintViolationListInterface
     */
    public function getValidationError(): ?ConstraintViolationListInterface;
    
    /**
     * @return array
     */
    public function getDenormalizationError(): array;
}
