<?php

namespace App\Exception;

use Throwable;

class ValidationExceptionCollection extends \Exception
{
    /**
     * @var ApiException[]
     */
    private $exceptions = [];

    public function __construct(
        array $exceptions,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        foreach ($exceptions as $exception) {
            if (!$exception instanceof ApiException) {
                throw new \RuntimeException('Only ApiException type available.');
            }

            $this->exceptions[] = $exception;
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ApiException[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
