<?php

namespace App\Service\HandlerException;

use App\Interfaces\DefaultListenerExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\ApiException;
use App\Service\ExceptionListener;
use Throwable;

/**
 * Class BaseHandler
 */
abstract class BaseHandler
{
    /**
     * @var int
     */
    public int $priority = -1;

    /**
     * @var int
     */
    protected int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var DefaultListenerExceptionInterface | ExceptionListener
     */
    protected $listener;

    /**
     * @return void
     */
    public function addCommonInfo()
    {
        if ($this->listener->isDev()) {
            $this->listener->getResponse()->setResponse([
                '__debug' => json_decode(json_encode($this->listener->getException()->getTrace()), true)
            ]);
        }
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param Throwable $exception
     * @param null $relatedField
     * @param array $dataTranslation
     *
     * @return ApiException
     */
    public function getException(Throwable $exception, $relatedField = null, $dataTranslation = []): ApiException
    {
        if (!$exception instanceof ApiException) {
            $ex = (new ApiException(
                $this->listener->getTranslator()->trans($exception->getMessage(), $dataTranslation, 'exception'),
                $this->statusCode,
                $relatedField
            ))->setType((new ReflectionClass($exception))->getName());
        } else {
            $ex = $exception;
        }

        $dataTranslation = array_merge(
            $dataTranslation,
            $ex->getTranslationParameters()
        );

        try {
            $reflectionProperty = new ReflectionProperty(get_class($ex), 'message');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($ex, $this->listener->getTranslator()->trans($exception->getMessage(), $dataTranslation, 'exception'));
        } catch (ReflectionException $reflectionException) {

        }

        try {
            $reflectionProperty = new ReflectionProperty(get_class($ex), 'message');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($ex, $this->listener->getTranslator()->trans($exception->getMessage(), $dataTranslation, 'exception'));
        } catch (ReflectionException $reflectionException) {

        }

        return $ex;
    }
}
