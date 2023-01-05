<?php

namespace App\Filter;

use App\Entity\Appointment\Appointment;
use App\Entity\Reference\Profession;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Class SearchPetFilter
 * @package App\Filter
 */
class AccessFilter extends BaseFilter
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
        return $entityClass === Appointment::class;
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

        switch (true) {
            //Если поиск только по владельцу или только по животному
            case ((isset($filtersRequest['owner']) || isset($filtersRequest['pet'])) && count($filtersRequest) === 1):
            case $this->authorizationChecker->isGranted('ROLE_APPOINTMENT_ADMIN'):
                break;
            case $this->authorizationChecker->isGranted('ROLE_LEAVING_ADMIN'):
                break;
                
            case $this->authorizationChecker->isGranted('ROLE_CASHIER'):
            case $this->authorizationChecker->isGranted('ROLE_RECEPTIONIST'):
                $queryBuilder->leftJoin($mainAlias.'.unit', 'u')
                    ->andWhere('u.id = :unitId')
                    ->setParameter('unitId', $user->getUnit() ? $user->getUnit()->getId() : 0);
                break;

            case $this->authorizationChecker->isGranted('ROLE_DOCTOR'):
                $professionIds = $user->getProfessions()->map(function(Profession $profession) {
                    return $profession->getId();
                })->getValues() ?? [0];

                $queryBuilder
                    ->leftJoin($mainAlias.'.unit', 'u')
                    ->leftJoin($mainAlias.'.profession', 'p')
                    ->leftJoin($mainAlias.'.user', 's')
                    ->andWhere(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq('s.id', $user->getId()),
                            $queryBuilder->expr()->andX(
                                $queryBuilder->expr()->eq('u.id', $user->getUnit() ? $user->getUnit()->getId() : 0),
                                $queryBuilder->expr()->in('p.id', $professionIds),
                                $queryBuilder->expr()->isNull($mainAlias.'.user')
                            )
                        )
                    );
                break;
        }
    }
}
