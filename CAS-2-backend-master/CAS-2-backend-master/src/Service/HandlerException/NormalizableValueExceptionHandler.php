<?php

namespace App\Service\HandlerException;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use App\Packages\Response\BaseResponse;

class NormalizableValueExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * Bad request
     * @var int
     */
    protected int $statusCode = 400;
    
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

        $ex = $this->getException($exception)->setType(NotNormalizableValueException::class);
        $apiResponse->addError($ex);

        return $apiResponse;
    }
    
    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof NotNormalizableValueException;
    }
}
