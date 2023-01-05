<?php

namespace App\Filter;

use App\Entity\Cash\CashReceipt;
use App\Entity\Reference\Unit;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class CashReceiptAccessFilter
 * @package App\Filter
 */
class CashReceiptAccessFilter extends BaseFilter
{
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @required
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @required
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === CashReceipt::class;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$this->authorizationChecker->isGranted('ROLE_ROOT') && !$this->authorizationChecker->isGranted('ROLE_APPOINTMENT_ADMIN')) {
            $queryBuilder->leftJoin($mainAlias.'.cashier', 'c')
            ->leftJoin(Unit::class, 'u', 'WITH', 'u.id = c.unit')
            ->andWhere('u.id = :unitId')
            ->setParameter('unitId', $user->getUnit() ? $user->getUnit()->getId() : 0);
        }
    }
}
