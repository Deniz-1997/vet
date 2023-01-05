<?php

namespace App\Service\HandlerException;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationErrorHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * @var int
     */
    protected int $statusCode = Response::HTTP_UNAUTHORIZED;
    
    public function __construct(DefaultListenerExceptionInterface $listener)
    {
        $this->listener = $listener;
    }

    public function handle(): ApiResponseInterface
    {
        /** @var BaseResponse $apiResponse */
        $apiResponse = $this->listener->getResponse()->statusError();
        $exception = $this->listener->getException();
        $this->addCommonInfo();

        $this->headers = [];

        $apiResponse->addError((new ApiException($exception->getMessage(), $this->statusCode))->setType(AuthenticationException::class));

        return $apiResponse;
    }
    
    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof AuthenticationException;
    }
}
