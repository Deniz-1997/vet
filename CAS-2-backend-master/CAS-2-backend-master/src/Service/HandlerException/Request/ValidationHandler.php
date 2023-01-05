<?php

namespace App\Service\HandlerException\Request;

use App\Exception\ValidationExceptionCollection;
use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Service\HandlerException\BaseHandler;

class ValidationHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * @param DefaultListenerExceptionInterface $listener
     */
    public function __construct(DefaultListenerExceptionInterface $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @return boolean
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof ValidationExceptionCollection;
    }

    /**
     * @return ApiResponseInterface
     */
    public function handle(): ApiResponseInterface
    {
        $response = $this->listener->getResponse();
        $exceptions = $this->listener->getException();

        foreach ($exceptions->getExceptions() as $exception) {
            $response->addError($exception);
        }

        return $response;
    }
}
