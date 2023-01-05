<?php

namespace App\Filter;

use App\Interfaces\FilterInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use App\Packages\Annotation\IgnoreDeleted;

/**
 * Class DeletedFilter
 */
class DeletedFilter implements FilterInterface
{
    /** @var Reader */
    private $annotationReader;

    /** @var array */
    private $ignoredProperties = [];

    /** @var array */
    private $reflectionCache = [];

    /**
     * DeletedFilterInterface constructor.
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function support(string $entityClass, array $filtersRequest): bool
    {
        $reflectionClass = $this->getReflectionClass($entityClass);

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            /** @var IgnoreDeleted $ignoreDeleted */
            if ($ignoreDeleted = $this->annotationReader->getPropertyAnnotation($reflectionProperty, IgnoreDeleted::class)) {
                $this->ignoredProperties[$reflectionProperty->getName()] = $ignoreDeleted;
            }
        }

        return !empty($this->ignoredProperties);
    }

    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        /** @var Join[] $joins */
        $joins = $queryBuilder->getDQLPart('join');
        $joins = isset($joins[$mainAlias]) ? $joins[$mainAlias] : [];

        $entityClassMetadata = $queryBuilder->getEntityManager()->getClassMetadata($entityClass);

        $joinAlias = null;
        foreach ($this->ignoredProperties as $ignoredProperty => $ignoredPropertyAnnotation) {
            if (!$entityClassMetadata->hasAssociation($ignoredProperty)) {
                throw new \RuntimeException(sprintf('Class %s have no %s field in metadata', $entityClass, $ignoredProperty));
            }

            $targetClass = $entityClassMetadata->getAssociationMapping($ignoredProperty)['targetEntity'];
            if (!property_exists($targetClass, 'deleted')) {
                throw new \RuntimeException(sprintf('Class %s not found deleted property. Not use @IgnoreAnnotation for field %s#%s', $targetClass, $entityClass, $ignoredProperty));
            }

            $joinDQL = sprintf('%s.%s', $mainAlias, $ignoredProperty);

            foreach ($joins as $join) {
                if ($join->getJoin() == $joinDQL) {
                    $joinAlias = $join->getAlias(); //Join already exists
                }
            }
            
            if (!$joinAlias) {
                $joinAlias = $ignoredProperty[0]; //Use first character as join alias
                $condition = str_replace('{alias}', $joinAlias, '{alias}.deleted = false');
                $queryBuilder->leftJoin($joinDQL, $joinAlias, Join::WITH, $condition);
            }

            $queryBuilder->addSelect($joinAlias);
        }
    }

    /**
     * @param $class
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function getReflectionClass($class)
    {
        if (!isset($this->reflectionCache[$class])) {
            $this->reflectionCache[$class] = new \ReflectionClass($class);
        }

        return $this->reflectionCache[$class];
    }
}
