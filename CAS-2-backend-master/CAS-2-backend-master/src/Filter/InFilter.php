<?php

namespace App\Filter;

use Doctrine\ORM\QueryBuilder;

/**
 * Class InFilter
 */
class InFilter extends BaseFilter
{
    public function support(string $entityClass, array $filtersRequest): bool
    {
        foreach ($filtersRequest as $field => $filter) {
            if (is_array($filter) && isset($filter['id']) && is_array($filter['id'])) {
                return true;
            }
        }

        return false;
    }

    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $filterData = [];
        foreach ($filtersRequest as $field => $filterForField) {
            if (is_array($filterForField) && isset($filterForField['id']) && is_array($filterForField['id'])) {
                $filterData[$field] = $filterForField['id'];
                if (count($filterForField) == 1) {
                    unset($filtersRequest[$field]['id']);
                } else {
                    unset($filtersRequest[$field]);
                }
            }
        }

        $metaData = $queryBuilder->getEntityManager()->getClassMetadata($entityClass);
        foreach ($metaData->getAssociationMappings() as $associationMapping) {
            foreach ($filterData as $fieldName => $ids) {
                if ($associationMapping['fieldName'] === $fieldName) {
                    if (!$alias = $this->getJoinAlias($queryBuilder, $mainAlias, $fieldName)) {
                        $alias = $fieldName;
                        $queryBuilder->join($mainAlias.'.'.$fieldName, $alias);
                    }

                    $queryBuilder->andWhere($queryBuilder->expr()->in($alias.'.id', $ids));
                }
            }
        }
    }
}
