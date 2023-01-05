<?php

namespace App\Interfaces\CRUD;

/**
 * Interface AddItemServiceInterface
 */
interface AddItemInterface
{
    /**
     * @param string $data
     * @param string $objectName
     * @param null   $validationGroup
     * @param array  $inputSerializationContext
     * @param array  $outputSerializationContext
     *
     * @return mixed
     */
    public function add(string $data, string $objectName, $validationGroup = null, array $inputSerializationContext = [], array $outputSerializationContext = []);
}
