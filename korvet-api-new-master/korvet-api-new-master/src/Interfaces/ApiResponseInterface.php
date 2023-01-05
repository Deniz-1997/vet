<?php

namespace App\Interfaces;

use App\Packages\Response\BaseResponse;
use App\Service\DeserializeService;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Serializer\Serializer;

/**
 * Interface ApiResponseInterface
 */
interface ApiResponseInterface
{
    /**
     * @return Response|BinaryFileResponse
     */
    public function send();
    
    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function addError(\Exception $exception);
    
    /**
     * @return BaseResponse
     */
    public function statusError(): BaseResponse;
    
    /**
     * @return bool
     */
    public function isStatusOk(): bool;
    
    /**
     * @return array|null|object
     */
    public function getResponse();
    
    /**
     * @param $response
     * @return BaseResponse
     */
    public function setResponse($response): BaseResponse;

    /**
     * @param int $code
     * @return BaseResponse
     */
    public function setHttpResponseCode(int $code): BaseResponse;
    
    /**
     * @param array $attributes
     * @return BaseResponse
     */
    public function setAttributes(array $attributes): BaseResponse;
    
    /**
     * @return Serializer|SerializeService
     */
    public function getSerializer();
    
    /**
     * @return DeserializeService
     */
    public function getDeserializer(): DeserializeService;
    
    /**
     * @param array|null $keys
     * @return mixed
     */
    public function unsetErrors(?array $keys);

    /**
     * @param array|null $errors
     * @return static
     */
    public function setErrors(?array $errors): ApiResponseInterface;
    
    /**
     * @return array|null
     */
    public function getErrors(): ?array;
}
