<?php

namespace App\Service\HandlerException\Database;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Packages\Response\BaseResponse;
use App\Service\HandlerException\BaseHandler;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ORMExceptionHandler
 */
class ORMExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * @var int
     */
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

        $ex = $this->getException($exception);
        $apiResponse->addError($ex);

        return $apiResponse;
    }

    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof ORMException;
    }
}
