<?php

namespace App\Filter;

use App\Entity\Pet\Pet;
use Doctrine\ORM\QueryBuilder;

/**
 * Class PetIdentifierFilter
 */
class PetIdentifierFilter extends BaseFilter
{
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === Pet::class && isset($filtersRequest['identifiers']);
    }

    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $queryBuilder
            ->join(sprintf('%s.%s', $mainAlias, 'identifiers'), 'i')
            ->join('i.type', 't')
        ;

        $orWhere = $queryBuilder->expr()->orX();

        foreach ($filtersRequest['identifiers'] as $identifier) {
            $orWhere->add(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('i.value', $queryBuilder->expr()->literal($identifier['value'])),
                    $queryBuilder->expr()->eq('t.id', $queryBuilder->expr()->literal($identifier['type_id']))
                )
            );
        }

        $queryBuilder->andWhere($orWhere);

        unset($filtersRequest['identifiers']);
    }
}
