<?php

namespace App\Filter;

use App\Repository\Pet\PetRepository;
use Doctrine\ORM\QueryBuilder;
use App\Filter\BaseFilter;

/**
 * Class IgnoreOwnerFilter
 */
class IgnoreOwnerFilter extends BaseFilter
{
    /** @var PetRepository */
    private $petRepository;

    /**
     * IgnoreOwnerFilter constructor.
     * @param PetRepository $petRepository
     */
    public function __construct(PetRepository $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return isset($filtersRequest['ignore_owners']) && is_array($filtersRequest['ignore_owners']) && $filtersRequest['ignore_owners'];
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        if (!$aliasPetToOwner = $this->getJoinAlias($queryBuilder, $mainAlias, 'owners')) {
            $aliasPetToOwner = '_ipto';
            $queryBuilder->leftJoin(sprintf('%s.%s', $mainAlias, 'owners'), $aliasPetToOwner);
        }

        if (!$aliasOwner = $this->getJoinAlias($queryBuilder, $aliasPetToOwner, 'owner')) {
            $aliasOwner = '_io2';
            $queryBuilder->leftJoin(sprintf('%s.%s', $aliasPetToOwner, 'owner'), $aliasOwner);
        }

        $qb = $this->petRepository->createQueryBuilder('pt');
        $res = $qb
            ->select('pt.id')
            ->join('pt.owners', 'pto')
            ->join('pto.owner', 'o')
            ->where($qb->expr()->in('o.id', $filtersRequest['ignore_owners']))
            ->andWhere('pto.deleted = false')
            ->getQuery()
            ->getArrayResult()
        ;

        $ignorePetIds = array_map(function($pet) { return $pet['id']; }, $res);

        if ($ignorePetIds) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->notIn(sprintf('%s.%s', $mainAlias, 'id'), $ignorePetIds)
            );
        }

        unset($filtersRequest['ignore_owners']);
    }
}
