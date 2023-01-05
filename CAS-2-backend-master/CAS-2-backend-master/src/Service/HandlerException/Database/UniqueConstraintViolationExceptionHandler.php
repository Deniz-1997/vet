<?php

namespace App\Service\HandlerException\Database;

use App\Exception\ApiException;
use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Packages\Response\BaseResponse;
use App\Service\HandlerException\BaseHandler;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Response;

class UniqueConstraintViolationExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * @var int
     */
    protected int $statusCode = Response::HTTP_BAD_REQUEST;

    /**
     * UniqueConstraintViolationExceptionHandler constructor.
     *
     * @param DefaultListenerExceptionInterface $listener
     */
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

        $message = 'Unique violation';
        $arMessage = explode("\n", $exception->getMessage());
        if (count($arMessage) > 1) {
            $message .= ': ' . trim($arMessage[count($arMessage) - 1]);
        }
        $apiResponse->addError(new ApiException($message, $this->statusCode));

        return $apiResponse;
    }

    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof UniqueConstraintViolationException;
    }
}
