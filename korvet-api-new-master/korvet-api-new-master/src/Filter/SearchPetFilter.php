<?php

namespace App\Filter;

use App\Entity\Pet\Pet;
use App\Entity\Pet\PetIndexSearch;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SearchPetFilter
 * @package App\Filter
 */
class SearchPetFilter extends BaseFilter
{
    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === Pet::class && $this->request->query->get('search');
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

        $field = $mainAlias.'.name';
        $name = mb_strtolower(trim($keyword));
        $queryBuilder->andWhere(
            $queryBuilder->expr()->like('LOWER('.$field.')', $queryBuilder->expr()->literal('%'.$name.'%')),
        );

        $this->request->query->remove('search');
    }
}
