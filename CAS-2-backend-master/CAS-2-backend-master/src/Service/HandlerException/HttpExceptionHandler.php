<?php

namespace App\Service\HandlerException;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Packages\Response\BaseResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class HttpExceptionHandler
 * HttpExceptionInterface is a special type of exception that holds status code and header details
 */
class HttpExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * DenormalizeExceptionHandler constructor.
     * @param DefaultListenerExceptionInterface $listener
     */
    public function __construct(DefaultListenerExceptionInterface $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @return ApiResponseInterface
     */
    public function handle(): ApiResponseInterface
    {
        /** @var BaseResponse $apiResponse */
        $apiResponse = $this->listener->getResponse()->statusError();
        $exception = $this->listener->getException();

        $this->addCommonInfo();

        $this->statusCode = $exception->getStatusCode();
        $this->headers = $exception->getHeaders();
        $ex = $this->getException($exception)->setType(HttpExceptionInterface::class);
        if ($exception->getStatusCode() < 500) { //Http exceptions less than 500 (internal errors)
            $apiResponse->addError($ex);
        }

        return $apiResponse;
    }
    
    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof HttpExceptionInterface;
    }
}
