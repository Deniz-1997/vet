<?php

namespace App\Interfaces\CRUD;

use App\Packages\Response\BaseResponse;

/**
 * Interface CrudBatchServiceInterface
 */
interface CrudBatchServiceInterface
{
    /**
     * @param string $data
     * @param string $entityName
     * @param string $dtoName
     * @param array|null $params
     *
     * @return mixed|BaseResponse
     */
    public function handleCreate(string $data, string $entityName, string $dtoName, ?array $params = []);

    /**
     * @param string $data
     * @param string $entityName
     * @param string $dtoName
     * @param array|null $params
     *
     * @return mixed|BaseResponse
     */
    public function handleUpdate(string $data, string $entityName, string $dtoName, ?array $params = []);

    /**
     * @param string $data
     * @param string $entityName
     * @param string $dtoName
     * @param array|null $params
     *
     * @return mixed|BaseResponse
     */
    public function handleDelete(string $data, string $entityName, string $dtoName, ?array $params = []);

    /**
     * @param string $data
     * @param string $entityName
     * @param string $dtoName
     * @param array|null $params
     *
     * @return mixed|BaseResponse
     */
    public function handleReplace(string $data, string $entityName, string $dtoName, ?array $params = []);
}
