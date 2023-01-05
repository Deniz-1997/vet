<?php

namespace App\Service\HandlerException;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\Response\BaseResponse;

/**
 * Class InvalidArgumentExceptionHandler
 */
class InvalidArgumentExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    protected int $statusCode = Response::HTTP_BAD_REQUEST;
    
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

        $ex = $this->getException($exception)->setType(\InvalidArgumentException::class);
        $apiResponse->addError($ex);

        return $apiResponse;
    }
    
    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof \InvalidArgumentException;
    }
}
