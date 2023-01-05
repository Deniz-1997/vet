<?php

namespace App\Filter;

use App\Entity\Appointment\Appointment;
use App\Entity\Appointment\AppointmentIndexSearch;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SearchAppointmentFilter
 */
class FullTextSearchFilter extends BaseFilter
{

    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === Appointment::class && $this->request->query->has('search');
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $keyword = mb_strtolower($this->request->query->get('search'));
        $parameterName = 'search_owner';

        $queryBuilder->join($mainAlias.'.owner', 'o');

        $queryBuilder->orWhere(sprintf("LOWER(o.name) LIKE :%s", $parameterName))
        ->setParameter($parameterName, '%'.$keyword.'%');

        $queryBuilder->orWhere(sprintf("LOWER(o.phone) LIKE :%s", $parameterName))
        ->setParameter($parameterName, '%'.$keyword.'%');

        $this->request->query->remove('search'); 
    }
}
