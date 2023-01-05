<?php

namespace App\Filter;

use App\Entity\Shop\ShopOrder;
use App\Entity\Shop\ShopSettings;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SearchPetFilter
 * @package App\Filter
 */
class ShopAccessFilter extends BaseFilter
{
    /** @var Security */
    private $authorizationChecker;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @required
     * @param Security $authorizationChecker
     */
    public function setAuthorizationChecker(Security $authorizationChecker)
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
        return $entityClass === ShopOrder::class || $entityClass === ShopSettings::class;
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
            $queryBuilder->leftJoin($mainAlias.'.unit', 'u')
            ->andWhere('u.id = :unitId')
            ->setParameter('unitId', $user->getUnit() ? $user->getUnit()->getId() : 0);
        }
    }
}
