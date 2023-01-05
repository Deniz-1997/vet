<?php

namespace App\Filter;

use App\Entity\Reference\Action;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class AccessActionFilter extends BaseFilter
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var RoleHierarchyInterface */
    private $roleHierarchy;

    /**
     * @required
     * @param TokenStorageInterface $tokenStorage
     * @return $this
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): self
    {
        $this->tokenStorage = $tokenStorage;
        return $this;
    }

    /**
     * @required
     * @param RoleHierarchyInterface $roleHierarchy
     * @return $this
     */
    public function setRoleHierarchy(RoleHierarchyInterface $roleHierarchy): self
    {
        $this->roleHierarchy = $roleHierarchy;
        return $this;
    }

    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === Action::class && $this->tokenStorage->getToken();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $grantedRoles = $this->roleHierarchy->getReachableRoleNames(
            $this->tokenStorage->getToken()->getRoleNames()
        );

        $grantedRoles = array_map(function($role) {
            return substr(mb_strtoupper($role), 0, 5) !== 'ROLE_' ? 'ROLE_'.mb_strtoupper($role) : $role;
        }, $grantedRoles);

        $queryBuilder
            ->leftJoin($mainAlias.'.roles', 'r')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->in('r.code', ':grantedRoles'),
                    $queryBuilder->expr()->isNull('r.id')
                )
            )
            ->setParameter('grantedRoles', $grantedRoles);
    }
}
