<?php

namespace App\Filter;

use App\Entity\Reference\SupervisedObjects;
use App\Entity\Reference\BusinesEntity;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SearchSuperviserObjectFilter
 * @package App\Filter
 */
class SearchSuperviserObjectFilter extends BaseFilter
{
    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === SupervisedObjects::class && $this->request->query->get('search');
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $keyword = $this->request->query->get('search');
        $search = mb_strtolower(trim($keyword));

        $queryBuilder
        ->leftJoin(
            BusinesEntity::class,
            'be',
            'WITH',
            'be.id = ' . $mainAlias . '.businessEntity'
        )
        ->orWhere(
            $queryBuilder->expr()->like('be.inn', $queryBuilder->expr()->literal('%'.$search.'%')),
        );
    }
}
