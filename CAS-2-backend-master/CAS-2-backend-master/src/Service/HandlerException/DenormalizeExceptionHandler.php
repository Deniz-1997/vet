<?php

namespace App\Service\HandlerException;

use App\Exception\DenormalizeExceptionInterface;
use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\Response\BaseResponse;

/**
 * Class DenormalizeExceptionHandler
 */
class DenormalizeExceptionHandler extends BaseHandler implements HandlerErrorInterface
{
    /**
     * @var int
     */
    protected int $statusCode = Response::HTTP_BAD_REQUEST;
    
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
        /** @var BaseResponse $apiResponse */
        $apiResponse = $this->listener->getResponse()->statusError();
        $this->addCommonInfo();
        foreach ($this->listener->getException()->getDenormalizeExceptions() as $denormalizeException) {
            if (is_array($denormalizeException)) {
                $this->formingException($denormalizeException, $apiResponse);
            } else {
                $ex = $this->getException($denormalizeException, $denormalizeException->getField());
                $ex->setType(DenormalizeExceptionInterface::class);
                // remove errors with empty message
                if (!empty($ex->getMessage())) {
                    $apiResponse->addError($ex);
                }
            }

        }

        return $apiResponse;
    }

    /**
     * @param array $denormalizeException
     * @param BaseResponse $apiResponse
     * @return mixed
     */
    private function formingException(array $denormalizeException, BaseResponse $apiResponse)
    {
        foreach ($denormalizeException as $exception) {
            if (is_array($exception)) {
                return $this->formingException($exception, $apiResponse);
            } else {
                $ex = $this->getException($exception, $exception->getField());
                if (!empty($ex->getMessage())) {
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
        return $this->listener->getException() instanceof DenormalizeExceptionInterface;
    }
}
