<?php

namespace App\Exception;

/**
 * Class ErrorResponseException
 */
class ErrorResponseException extends \Exception
{
    /** @var string */
    private $errorCode;

    /** @var string */
    private $errorResponseMessage;

    /**
     * ErrorResponseException constructor.
     * @param string $errorCode
     * @param string $errorResponseMessage
     */
    public function __construct(string $errorCode, string $errorResponseMessage)
    {
        $this->errorCode = $errorCode;
        $this->errorResponseMessage = $errorResponseMessage;

        parent::__construct(sprintf('%s: %s', $errorCode, $errorResponseMessage));
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorResponseMessage(): string
    {
        return $this->errorResponseMessage;
    }
}
