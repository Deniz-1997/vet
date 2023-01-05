<?php

namespace App\Filter\Type;

use App\Entity\Owner\Embeddable\FullName;
use App\Interfaces\TypeFilterInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class FullNameTypeFilter
 */
class FullNameTypeFilter implements TypeFilterInterface
{
    /** @var FullNameNestedFilter */
    private $nestedFullNameFilter;

    /**
     * FullNameTypeFilter constructor.
     *
     * @param FullNameNestedFilter $nestedFullNameFilter
     */
    public function __construct(FullNameNestedFilter $nestedFullNameFilter)
    {
        $this->nestedFullNameFilter = $nestedFullNameFilter;
    }

    /**
     * @param TypeInfo $typeInfo
     * @param $searchValue
     * @return bool
     */
    public function supportType(TypeInfo $typeInfo, $searchValue): bool
    {
        return $typeInfo->getType()->getClassName() === FullName::class && is_scalar($searchValue);
    }

    /**
     * @param QueryBuilder $builder
     * @param TypeInfo $typeInfo
     * @param string $field
     * @param $searchValue
     */
    public function handleType(QueryBuilder $builder, TypeInfo $typeInfo, string $field, $searchValue)
    {
        $nameData = explode(' ', trim($searchValue));
        $this->nestedFullNameFilter->applyFilter($builder, [$field.'.name', $field.'.middleName', $field.'.lastName'], $nameData);
    }
}
