<?php

namespace App\Filter;

use App\Entity\Owner;
use App\Entity\User\User;
use App\Filter\Type\FullNameNestedFilter;
use Doctrine\ORM\QueryBuilder;
use App\Filter\BaseFilter;

/**
 * Class FullNameFilter
 */
class FullNameFilter extends BaseFilter
{
    /** @var FullNameNestedFilter */
    private $nestedFullNameFilter;

    /**
     * @required
     * @param FullNameNestedFilter $nestedFullNameFilter
     */
    public function setNestedFullNameFilter(FullNameNestedFilter $nestedFullNameFilter)
    {
        $this->nestedFullNameFilter = $nestedFullNameFilter;
    }

    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return ($entityClass === User::class) && isset($filtersRequest['fullName']) && is_string($filtersRequest['fullName']);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        if($entityClass === Owner::class){
            $field=$mainAlias.'.name';
            $name = mb_strtolower(trim($filtersRequest['fullName']));
            $this->nestedFullNameFilter->applyOwnerFilter($queryBuilder, $field, $name);
        }
        else{
            $fields = [$mainAlias.'.name', $mainAlias.'.surname', $mainAlias.'.patronymic'];
            $nameData = explode(' ', trim($filtersRequest['fullName']));
            $this->nestedFullNameFilter->applyFilter($queryBuilder, $fields, $nameData);
        }

        unset($filtersRequest['fullName']);
    }
}
