<?php

namespace App\Service\HandlerException;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use App\Exception\ApiException;
use \App\Packages\Response\BaseResponse;

/**
 * Class ValidationAndNormalizationExceptionHandler
 */
class ValidationAndNormalizationExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * @var int
     */
    protected int $statusCode = Response::HTTP_BAD_REQUEST;

    /**
     * @var array
     */
    private static array $stackErrorFieldDeserialize = [];

    /**
     * DenormalizeExceptionHandler constructor.
     *
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
        self::$stackErrorFieldDeserialize = [];
        /** @var BaseResponse $apiResponse */
        $apiResponse = $this->listener->getResponse()->statusError();
        $this->addCommonInfo();
        /** @var ValidationAndNormalizationException $wrapException */
        $wrapException = $this->listener->getException();

        $denormalizeExceptions = $wrapException->getDenormalizationError() ?? [];
        foreach ($denormalizeExceptions as $denormalizeException) {
            if (is_array($denormalizeException)) {
                $this->formingException($denormalizeException, $apiResponse);
            } else {
                $ex = $this->getException($denormalizeException, $denormalizeException->getField());
                $ex->setType(ValidationAndNormalizationException::class);
                // remove errors with empty message
                if (!empty($ex->getMessage())) {
                    $apiResponse->addError($ex);
                }
            }
        }

        $validationErrors = $wrapException->getValidationError() ?? [];
        /** @var ConstraintViolation $constraint */
        foreach ($validationErrors as $constraint) {
            // пропускаем если ошибка валидатора выпала на тоже поле, что ошибка deserialization
            if (isset(self::$stackErrorFieldDeserialize[$constraint->getPropertyPath()])) {
                continue;
            }
            $ex = new ApiException(
                $this->listener->getTranslator()->trans($constraint->getMessage(), [], 'exception'),
                $this->statusCode,
                $constraint->getPropertyPath()
            );
            $ex->setType(ValidationAndNormalizationException::class);
            // remove errors with empty message
            if (!empty($ex->getMessage())) {
                $apiResponse->addError($ex);
            }

        }

        return $apiResponse;
    }

    /**
     * @param array $denormalizeException
     * @param BaseResponse $apiResponse
     *
     * @return mixed
     */
    private function formingException(array $denormalizeException, BaseResponse $apiResponse)
    {
        foreach ($denormalizeException as $exception) {
            if (is_array($exception)) {
                return $this->formingException($exception, $apiResponse);
            } else {
                $field = $exception->getField();
                self::$stackErrorFieldDeserialize[$field] = $field;
                $ex = $this->getException($exception, $field);
                $message = $ex->getMessage() ? $ex->getMessage() : 'error.wrong_type_of_property';
                $reflClass = new \ReflectionClass($ex);
                foreach ($reflClass->getProperties() as $property) {
                    if ($property->getName() === 'message') {
                        $property->setAccessible(true);
                        $property->setValue($ex, $this->listener->getTranslator()->trans($message, ['property' => $field], 'exception'));
                    }
                }
                if ($message || $exception->getField()) {
                    $apiResponse->addError($ex);
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function supportException(): bool
    {
        return $this->listener->getException() instanceof ValidationAndNormalizationException;
    }
}
