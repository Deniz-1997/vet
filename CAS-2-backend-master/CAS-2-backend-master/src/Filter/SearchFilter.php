<?php

namespace App\Filter;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SearchFilter
 */
class SearchFilter extends BaseFilter
{

    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $this->request->query->has('search');
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     * @throws MappingException
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $classMetadata = $queryBuilder->getEntityManager()->getClassMetadata($entityClass);
        $configuration = $queryBuilder->getEntityManager()->getConfiguration();
        $search = mb_strtolower($this->request->query->get('search'));
        $search = trim($search);
        foreach ($classMetadata->getFieldNames() as $fieldName) {
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);
            $parameterName = '_filter_'.str_replace('.', '_', $fieldName); //Fix embedded search

            foreach ($classMetadata->getAssociationMappings() as $associationMapping) {
                if ($fieldName === $associationMapping['fieldName']) {
                    continue;
                }
            }

            if ($this->isString($search)) {
                if (in_array($fieldMapping['type'], [Type::STRING, Type::TEXT])) {
                    $queryBuilder
                        ->orWhere(sprintf('LOWER(%s.%s) LIKE :%s', $mainAlias, $fieldName, $parameterName))
                        ->setParameter($parameterName, '%'.$search.'%');
                }
                elseif (in_array($fieldMapping['type'], [TYPE::JSON])) {
                    $queryBuilder
                    ->orWhere(sprintf('LOWER(CAST(%s.%s AS STRING)) LIKE :%s', $mainAlias, $fieldName, $parameterName))
                    ->setParameter($parameterName, '%'.$search.'%');
                }
            } else {
                if (!$configuration->getCustomStringFunction('cast')) {
                    continue;
                }

                if (in_array($fieldMapping['type'], [Type::INTEGER, Type::SMALLINT, Type::BIGINT, Type::FLOAT])) {
                    $queryBuilder
                        ->orWhere(sprintf('LOWER(CAST(%s.%s AS STRING)) LIKE :%s', $mainAlias, $fieldName, $parameterName))
                        ->setParameter($parameterName, '%'.$search.'%');

                } elseif (in_array($fieldMapping['type'], [Type::STRING, Type::TEXT])) {
                    $queryBuilder
                        ->orWhere(sprintf('LOWER(%s.%s) LIKE :%s', $mainAlias, $fieldName, $parameterName))
                        ->setParameter($parameterName, '%'.$search.'%');
                } elseif (in_array($fieldMapping['type'], [TYPE::JSON])) {
                    $queryBuilder
                    ->orWhere(sprintf('LOWER(CAST(%s.%s AS STRING)) LIKE :%s', $mainAlias, $fieldName, $parameterName))
                    ->setParameter($parameterName, '%'.$search.'%');
                }
            }
        }
    }

    private function isString($value): bool
    {
        if (strpos($value, '.') !== false) {
            if (strval(floatval($value)) == $value) {
                return false;
            }
        }

        if (strval(intval($value)) == $value) {
            return false;
        }

        return true;
    }
}
