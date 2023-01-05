<?php

namespace App\Interfaces;

use App\Filter\Type\TypeInfo;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface TypeFilterInterface
 */
interface TypeFilterInterface
{
    /**
     * @param TypeInfo $typeInfo
     * @param $searchValue
     * @return bool
     */
    public function supportType(TypeInfo $typeInfo, $searchValue): bool;

    /**
     * @param QueryBuilder $builder
     * @param TypeInfo $typeInfo
     * @param string $field
     * @param $searchValue
     * @return mixed
     */
    public function handleType(QueryBuilder $builder, TypeInfo $typeInfo, string $field, $searchValue);
}
