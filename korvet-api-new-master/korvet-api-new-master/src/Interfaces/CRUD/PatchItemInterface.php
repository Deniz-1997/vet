<?php

namespace App\Interfaces\CRUD;

use App\Packages\Response\BaseResponse as ApiResponse;

/**
 * Interface PatchItemInterface
 */
interface PatchItemInterface
{
    /**
     * @param string      $id
     * @param string      $data
     * @param string      $objectName
     * @param string|null $validationGroup
     *
     * @return ApiResponse
     */
    public function patch(string $id, string $data, string $objectName, string $validationGroup = null): ApiResponse;
}
