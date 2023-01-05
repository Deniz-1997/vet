<?php

namespace App\Interfaces\CRUD;

use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Packages\Response\BaseResponse as ApiResponse;
use App\Service\HandlerException\Validation\ValidationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Interface UpdateItemInterface
 */
interface UpdateItemInterface
{
    /**
     * @param string $id
     * @param string $data
     * @param string $objectName
     * @param null   $validationGroup
     * @param array  $outputSerializationContext
     * @param array  $inputSerializationContext
     *
     * @return BaseResponse
     * @throws ApiException
     * @throws ValidationException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(string $id, string $data, string $objectName, $validationGroup = null, array $outputSerializationContext = [], array $inputSerializationContext = []): ApiResponse;
}
