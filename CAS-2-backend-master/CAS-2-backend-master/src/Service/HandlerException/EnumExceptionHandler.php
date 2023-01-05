<?php

namespace App\Service\HandlerException;

use App\Enum\EnumException;
use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Packages\Response\BaseResponse;
use ReflectionClass;
use ReflectionException;

class EnumExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * Bad request
     * @var int
     */
    protected int $statusCode = 400;

    /**
     * EnumExceptionHandler constructor.
     *
     * @param DefaultListenerExceptionInterface $listener
     */
    public function __construct(DefaultListenerExceptionInterface $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @return ApiResponseInterface
     * @throws ReflectionException
     */
    public function handle(): ApiResponseInterface
    {
        /** @var BaseResponse $apiResponse */
        $apiResponse = $this->listener->getResponse()->statusError();
        /** @var EnumException $exception */
        $exception = $this->listener->getException();

        $this->addCommonInfo();

        $this->statusCode = $exception->getCode();
        $ex = $this->getException($exception, 'code', ['{{class}}' => '['.(new ReflectionClass($exception->getClassName()))->getShortName().']', '{{value}}' => $exception->getValue(), '{{allow_values}}' => implode(', ', $exception->getAllowed())]);
        $ex->setType(EnumException::class);
        if ($exception->getCode() < 500) {
            $apiResponse->addError($ex);
        }

        return $apiResponse;
    }

    public function supportException(): bool
    {
        return $this->listener->getException() instanceof EnumException;
    }
}
