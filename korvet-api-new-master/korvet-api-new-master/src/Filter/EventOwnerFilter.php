<?php

namespace App\Filter;

use App\Entity\Event;
use Doctrine\ORM\QueryBuilder;
use App\Filter\BaseFilter;

/**
 * Class EventOwnerFilter
 */
class EventOwnerFilter extends BaseFilter
{
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === Event::class && isset($filtersRequest['owners']) && is_array($filtersRequest['owners']);
    }

    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $queryBuilder
            ->join($mainAlias.'.pet', 'pet')
            ->join('pet.owners', 'owners')
            ->where($queryBuilder->expr()->in('owners.id', $filtersRequest['owners']))
        ;

        unset($filtersRequest['owners']);
    }
}
