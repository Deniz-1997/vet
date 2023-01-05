<?php

namespace App\Filter;

use App\Interfaces\FilterInterface;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BaseFilter
 */
abstract class BaseFilter implements FilterInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * BaseFilter constructor.
     *
     * @param ?RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    protected function getJoinAlias(QueryBuilder $queryBuilder, $mainAlias, $fieldRelation)
    {
        /** @var Join[] $joins */
        $joins = $queryBuilder->getDQLPart('join');
        $joins = isset($joins[$mainAlias]) ? $joins[$mainAlias] : [];

        foreach ($joins as $join) {
            if ($join->getJoin() == $mainAlias.'.'.$fieldRelation) {
                return $join->getAlias();
            }
        }

        return null;
    }

    abstract public function support(string $entityClass, array $filtersRequest): bool;
    abstract public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest);
}
