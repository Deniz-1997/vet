<?php


namespace App\Interfaces;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Interface ValidationExceptionInterface
 */
interface ValidationExceptionInterface
{
    /**
     * @return ConstraintViolationListInterface
     */
    public function getConstraints(): ConstraintViolationListInterface;
    
    /**
     * @return array
     */
    public function getTransParams(): array;
}
