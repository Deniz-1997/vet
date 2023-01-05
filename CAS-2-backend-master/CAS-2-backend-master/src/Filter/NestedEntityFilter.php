<?php

namespace App\Filter;

use App\Entity\Appointment\Appointment;
use App\Filter\Type\TypeInfo;
use App\Interfaces\TypeFilterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use App\Service\CRUD\GetListService;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class NestedEntityFilter
 */
class NestedEntityFilter extends BaseFilter
{
    use FilterConditionHelperTrait;

    const HANDLED_FILTER_VALUE = '__FILTERED__';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var array */
    private $metaDataEntityCache = [];

    /** @var array */
    private $usedAliases = [];

    /** @var TypeFilterInterface[] */
    private $typeFilters;

    /** @var PropertyInfoExtractorInterface */
    private $propertyInfo;

    /** @var array */
    private $filterRequest;

    /** @var array */
    private $mapFilters = [];

    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @required
     * @param PropertyInfoExtractorInterface $propertyInfo
     */
    public function setPropertyInfo(PropertyInfoExtractorInterface $propertyInfo)
    {
        $this->propertyInfo = $propertyInfo;
    }

    /**
     * @param TypeFilterInterface[] $typeFilters
     */
    public function setTypeFilters(iterable $typeFilters)
    {
        VarDumper::dump([$typeFilters, 1]);die();
        $this->typeFilters = $typeFilters;
    }

    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        $this->createTypeFilterMap($entityClass, GetListService::DEFAULT_ALIAS, '', $filtersRequest);

        foreach ($filtersRequest as $filterKey => $filterValue) {
            if ($this->getAssociationMapping($entityClass, $filterKey) && $filterValue) {
                return true;
            }
        }

        return !empty($this->mapFilters);
    }

    private function createTypeFilterMap(string $entityClass, string $mainAlias, string $aliasToNestedClass, array $filtersRequest, array $keys = [])
    {
        $metadata = $this->getClassMetaData($entityClass);

        foreach ($filtersRequest as $filterKey => $filterValue) {
            $lastMainAlias = $mainAlias;

            if (isset($metadata->embeddedClasses[$filterKey])) {
                $nestedClass = $metadata->embeddedClasses[$filterKey]['class'];
                $aliasToNestedClass = $aliasToNestedClass ? $aliasToNestedClass.'.'.$filterKey : $filterKey;
            } elseif (!is_null($filterValue) && $associationMapping = $this->getAssociationMapping($entityClass, $filterKey)) {
                $nestedClass = $associationMapping['targetEntity'];
                $mainAlias = $this->addJoin($this->queryBuilder, $mainAlias, $filterKey, $entityClass, $nestedClass);
                $aliasToNestedClass = '';
            } else {
                continue;
            }

            $foundSupportedTypeFilter = false;
            $types = $this->propertyInfo->getTypes($entityClass, $filterKey);
            if ($types) {
                $typeInfo = new TypeInfo($nestedClass, $filterKey, $types[0]);

                if(!is_null($this->typeFilters)){
                    foreach ($this->typeFilters as $typeFilter) {
                        if ($typeFilter->supportType($typeInfo, $filterValue)) {
                            $foundSupportedTypeFilter = true;
                            $this->mapFilters[] = [
                                'keys' => $keys,
                                'alias' => $mainAlias,
                                'field' => $aliasToNestedClass,
                                'typeInfo' => $typeInfo,
                                'value' => $filterValue,
                            ];
                            $aliasToNestedClass = '';
                        }
                    }
                }
            }

            if (is_array($filterValue) && !$foundSupportedTypeFilter) {
                $keys[] = $filterKey;
                $this->createTypeFilterMap($nestedClass, $mainAlias, $aliasToNestedClass, $filterValue, $keys);
            }

            $mainAlias = $lastMainAlias;
            $keys = [];
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $entityClass
     * @param string       $mainAlias
     * @param array        $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $pa = PropertyAccess::createPropertyAccessor();

        $metadata = $this->getClassMetaData($entityClass);

        foreach ($this->mapFilters as $mapFilter) {
            foreach ($this->typeFilters as $typeFilter) {
                if ($typeFilter->supportType($mapFilter['typeInfo'], $mapFilter['value'])) {
                    $typeFilter->handleType($queryBuilder, $mapFilter['typeInfo'], $mapFilter['alias'].'.'.$mapFilter['field'], $mapFilter['value']);

                    $keys = $mapFilter['keys'];
                    for ($i = 0; $i < count($keys); $i++) {
                        $propertyPath = '['.implode('][', $keys).']';
                        $data = $pa->getValue($filtersRequest, $propertyPath);
                        if (is_array($data)) {
                            if (count($data) > 1) {
                                $propertyPath = $propertyPath.'['.$mapFilter['field'].']';
                            }

                            $pa->setValue($filtersRequest, $propertyPath, self::HANDLED_FILTER_VALUE);
                        }
                        array_pop($keys);
                    }

                    if (isset($filtersRequest[$mapFilter['field']]) && is_scalar($filtersRequest[$mapFilter['field']])) {
                        unset($filtersRequest[$mapFilter['field']]);
                    }
                }
            }
        }

        $this->removeEmptyFilters($filtersRequest);

        foreach ($filtersRequest as $filterField => $filterValue) {
            if (!($associationMapping = $this->getAssociationMapping($entityClass, $filterField)) && !isset($metadata->embeddedClasses[$filterField])) {
                continue;
            }

            if ($filterValue) {
                $this->handleRow($queryBuilder, $entityClass, $mainAlias, [$filterField => $filterValue]);
                if($entityClass!==Appointment::class && $filterField!=='owner'){
                    unset($filtersRequest[$filterField]);
                }
            }
        }
    }

    private function removeEmptyFilters(&$haystack)
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->removeEmptyFilters($haystack[$key]);
            }

            if ($haystack[$key] === self::HANDLED_FILTER_VALUE) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $entityClass
     * @param string       $alias
     * @param array        $row
     */
    private function handleRow(QueryBuilder $queryBuilder, string $entityClass, string $alias, array $row)
    {
        foreach ($row as $filterField => $filterValue) {
            if ($associationMapping = $this->getAssociationMapping($entityClass, $filterField)) {
                $newAlias = $this->addJoin($queryBuilder, $alias, $filterField, $entityClass, $associationMapping['targetEntity']);
                $this->handleRow($queryBuilder, $associationMapping['targetEntity'], $newAlias, $filterValue);
            } else {
                if (preg_match('/(\~|\=|\<\=|\>\=|\!\=|<|>|\!)/', $filterField, $matches)) {
                    $operator = $matches[1];
                    $filterField = str_replace($operator, '', $filterField);
                } else {
                    $operator = '=';
                }
                $field = null;
                if (is_array($filterValue)) {
                    foreach ($filterValue as $cn => $cnVal) {
                        if (is_string($cn)) {
                            $metadata = $this->metaDataEntityCache[$entityClass] ?? null;
                            if ($metadata && isset($metadata->embeddedClasses[$filterField])){
                                $field = sprintf('%s.%s.%s', $alias, $filterField, $cn);
                                $filterValue = $cnVal;
                            }
                        }
                    }
                }
                if (!$field) {
                    $field = sprintf('%s.%s', $alias, $filterField);
                }
                $queryBuilder->andWhere(
                    $this->createExpression($field, $filterValue, $operator)
                );
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $mainAlias
     * @param string       $filterField
     * @param string       $fromEntity
     * @param string       $toEntity
     * @return string
     */
    private function addJoin(QueryBuilder $queryBuilder, string $mainAlias, string $filterField, string $fromEntity, string $toEntity) : string
    {
        if (!$joinAlias = $this->getJoinAlias($queryBuilder, $mainAlias, $filterField)) {
            $joinAlias = $this->createAliasFor($filterField, $fromEntity, $toEntity);
            $queryBuilder->join(sprintf('%s.%s', $mainAlias, $filterField), $joinAlias);
        } else {
            $this->useAlias($fromEntity, $toEntity, $joinAlias);
        }

        return $joinAlias;
    }

    /**
     * @param string $filterField
     * @param string $fromEntity
     * @param string $toEntity
     * @return string
     */
    private function createAliasFor(string $filterField, string $fromEntity, string $toEntity)
    {
        $variant = '_'.$filterField;

        $counter = 0;
        while ($this->getAlias($fromEntity, $toEntity) === $variant) {
            $variant = $variant.$counter;
            $counter += 1;
        }

        $this->useAlias($fromEntity, $toEntity, $variant);

        return $variant;
    }

    /**
     * @param string $fromEntity
     * @param string $toEntity
     * @param string $alias
     */
    private function useAlias(string $fromEntity, string $toEntity, string $alias)
    {
        $this->usedAliases[$fromEntity.$toEntity] = $alias;
    }

    /**
     * @param string $fromEntity
     * @param string $toEntity
     * @return string|null
     */
    private function getAlias(string $fromEntity, string $toEntity): ?string
    {
        return isset($this->usedAliases[$fromEntity.$toEntity]) ? $this->usedAliases[$fromEntity.$toEntity] : null;
    }

    /**
     * @param string $targetEntity
     * @param string $fieldName
     * @return array|null
     */
    private function getAssociationMapping(string $targetEntity, string $fieldName)
    {
        $associationMappings = $this->getClassMetaData($targetEntity)->getAssociationMappings();

        foreach ($associationMappings as $associationMapping) {
            if ($associationMapping['fieldName'] === $fieldName) {
                return $associationMapping;
            }
        }

        return null;
    }

    /**
     * @param string $targetEntity
     * @return ClassMetadata
     */
    private function getClassMetaData(string $targetEntity)
    {
        if (!isset($this->metaDataEntityCache[$targetEntity])) {
            $this->metaDataEntityCache[$targetEntity] = $this->entityManager->getClassMetadata($targetEntity);
        }

        return $this->metaDataEntityCache[$targetEntity];
    }
}
