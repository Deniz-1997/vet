<?php

namespace App\Service\HandlerException\Validation;

use App\Interfaces\ValidationExceptionInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidationException
 */
class ValidationException extends \Exception implements ValidationExceptionInterface
{
    /**
     * @var array
     */
    private $constraints;
    /**
     * @var array
     */
    private array $transParams;
    
    /**
     * ValidationException constructor.
     *
     * @param ConstraintViolationListInterface $constraints
     * @param array                            $transParams
     * @param string                           $message
     * @param int                              $code
     * @param \Throwable|null                  $previous
     */
    public function __construct(ConstraintViolationListInterface $constraints, array $transParams = [], string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->constraints = $constraints;
        $this->transParams = $transParams;
    }
    
    /**
     * @return ConstraintViolationListInterface
     */
    public function getConstraints(): ConstraintViolationListInterface
    {
        return $this->constraints;
    }
    
    /**
     * @return array
     */
    public function getTransParams(): array
    {
        return $this->transParams;
    }
}
