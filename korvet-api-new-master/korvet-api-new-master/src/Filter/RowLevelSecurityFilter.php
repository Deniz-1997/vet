<?php

namespace App\Filter;

use App\Interfaces\FilterInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Packages\Event\RowLevelSecurityResolverEvent;

/**
 * Class RowLevelSecurityFilter
 */
class RowLevelSecurityFilter extends SQLFilter implements FilterInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @required
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $event = new RowLevelSecurityResolverEvent();

        $this->eventDispatcher->dispatch($event,RowLevelSecurityResolverEvent::NAME);
    }

    public function support(string $entityClass, array $filtersRequest): bool
    {
        return false;
    }

    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        // TODO: Implement handle() method.
    }
}
