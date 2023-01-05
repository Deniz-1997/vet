<?php

namespace App\Filter;

use App\Entity\ApiData\ApiQueue;
use App\Entity\Reference\Action;
use App\Entity\Reference\BusinesEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User\User;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Reference\Station;
use App\Entity\Security\Group;

class ApiQueryFilter extends BaseFilter
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
        return $entityClass === ApiQueue::class && $this->tokenStorage->getToken();
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
        if ($this->userIsInGroup($user, 'ROLE_ROOT')) {
            return;
        } else if ($this->userIsInGroup($user, 'ROLE_GOVERNMENT')) {
            $conn = $queryBuilder->getEntityManager()->getConnection();
            $request = "SELECT DISTINCT be.id FROM structure.busines_entity be
                        JOIN structure.supervised_objects so on so.businessentity_id = be.id
                        join reference.reference_station st on st.id = so.station_id
                        WHERE st.id in (select station_id from station_user where user_id = {$user->getId()});";
            $stmt = $conn->prepare($request);
            $result = $stmt->executeQuery()->fetchAllAssociative();
            $arr = [];
            foreach ($result as $item) {
                if (isset($item['id'])) {
                    array_push($arr, $item['id']);
                }
            }
            if (count($arr) > 0) {
                $queryBuilder->leftJoin($mainAlias . '.businessEntity', 'be')
                    ->andWhere($queryBuilder->expr()->in('be.id', ':beIds'))
                    ->setParameter('beIds', $arr);
            }
        } else if ($this->userIsInGroup($user, 'ROLE_BUSINESS_ENTITY')) {
            $conn = $queryBuilder->getEntityManager()->getConnection();
            $request = "SELECT be.id FROM structure.busines_entity be
                        JOIN busines_entity_user beu on beu.busines_entity_id = be.id
                        WHERE beu.user_id = {$user->getId()}";
            $stmt = $conn->prepare($request);
            $result = $stmt->executeQuery()->fetchAllAssociative();
            $arr = [];
            foreach ($result as $item) {
                if (isset($item['id'])) {
                    array_push($arr, $item['id']);
                }
            }
            if (count($arr) > 0) {
                $queryBuilder->leftJoin($mainAlias . '.businessEntity', 'be')
                    ->andWhere($queryBuilder->expr()->in('be.id', ':beIds'))
                    ->setParameter('beIds', $arr);
            }
        }
    }

    private function userIsInGroup(User $user, string $groupCode) : bool {
        /** @var Group[] $userGroups*/
        $userGroups = $user->getGroups()->toArray();
        foreach ($userGroups as $group) {
            if ($group->getCode() == $groupCode) {
                return true;
            }
        }
        return false;
    }
}
