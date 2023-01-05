<?php

namespace App\Interfaces\CRUD;

use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;

/**
 * Interface DeleteItemInterface
 */
interface DeleteItemInterface
{
    /**
     * @param string $id
     * @param string $object
     * @param array $serializationContext
     *
     * @return ApiResponse
     * @throws ApiException
     */
    public function deleteItem(string $id, string $object, array $serializationContext): ApiResponse;
}
