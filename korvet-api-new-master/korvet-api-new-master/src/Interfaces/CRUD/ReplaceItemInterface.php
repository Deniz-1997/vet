<?php

namespace App\Interfaces\CRUD;

use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Packages\Response\BaseResponse as ApiResponse;
use App\Service\HandlerException\Validation\ValidationException;

/**
 * Interface ReplaceItemInterface
 */
interface ReplaceItemInterface
{
    /**
     * @param string            $id
     * @param string            $data
     * @param string            $objectName
     * @param array|string|null $validationGroup
     * @param array             $outputSerializationContext
     * @param array             $inputSerializationContext
     *
     * @return BaseResponse
     * @throws ApiException
     * @throws ValidationException
     */
    public function replace(string $id, string $data, string $objectName, $validationGroup = null, array $outputSerializationContext = [], array $inputSerializationContext = []): ApiResponse;
}
