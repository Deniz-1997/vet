<?php

namespace App\Service\HandlerException\Validation;

use App\Exception\ApiException;
use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Packages\Response\BaseResponse;
use App\Service\ExceptionListener;
use App\Service\HandlerException\BaseHandler;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ValidationErrorHandler
 */
class ValidationErrorHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * @var int
     */
    protected int $statusCode = Response::HTTP_BAD_REQUEST;

    /**
     * DenormalizeExceptionHandler constructor.
     *
     * @param DefaultListenerExceptionInterface|ExceptionListener $listener
     */
    public function __construct(DefaultListenerExceptionInterface $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @inheritdoc
     */
    public function handle(): ApiResponseInterface
    {
        /** @var BaseResponse $apiResponse */
        $apiResponse = $this->listener->getResponse()->statusError();
        /** @var ValidationException $exception */
        $exception = $this->listener->getException();

        $this->addCommonInfo();

        foreach ($exception->getConstraints() as $constraint) {
            $ex = (new ApiException(
                $this->listener->getTranslator()->trans($constraint->getMessage(), [], 'exception'),
                $this->statusCode,
                $constraint->getPropertyPath()
            ))->setType(ValidationException::class);
            // remove errors with empty message
            if (!empty($ex->getMessage())) {
                $apiResponse->addError($ex);
            }

        }

        return $apiResponse;
    }

    /**
     * @inheritdoc
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof ValidationException;
    }
}
