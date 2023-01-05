<?php

namespace App\Service\HandlerException;

use App\Exception\ApiException;
use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Packages\Response\BaseResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class AuthenticationCredentialsNotFoundExceptionHandler extends BaseHandler implements HandlerErrorInterface
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

        $apiResponse->addError((new ApiException($exception->getMessage(), $this->statusCode))->setType(AuthenticationCredentialsNotFoundException::class));

        return $apiResponse;
    }

    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof AuthenticationCredentialsNotFoundException;
    }
}
