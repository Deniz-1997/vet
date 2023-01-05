<?php

namespace App\Service\HandlerException;

use App\Interfaces\ValidationAndNormalizationExceptionInterface;
use \Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidationAndNormalizationException
 */
class ValidationAndNormalizationException extends \Exception implements ValidationAndNormalizationExceptionInterface
{
    /**
     * @var ConstraintViolationListInterface
     */
    private ?ConstraintViolationListInterface $validationErrors;
    /**
     * @var array
     */
    private ?array $denormanizationErrors;

    public function __construct(?ConstraintViolationListInterface $validationErrors, ?array $denormanizationErrors, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->denormanizationErrors = $denormanizationErrors;
        $this->validationErrors = $validationErrors;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getValidationError(): ?ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }

    /**
     * @return array
     */
    public function getDenormalizationError(): array
    {
        return $this->denormanizationErrors;
    }
}
