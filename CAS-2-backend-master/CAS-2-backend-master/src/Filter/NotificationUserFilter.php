<?php

namespace App\Filter;

use App\Entity\Notifications\NotificationsList;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class NotificationUserFilter
 * @package App\Filter
 */
class NotificationUserFilter extends BaseFilter
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

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
        return $entityClass === NotificationsList::class && $this->request->query->get('search');
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
        $joins = $queryBuilder->getDQLPart('join');
        $keyword = $this->request->query->get('search');
        $name = mb_strtolower(trim($keyword));
        $field = $mainAlias.'.header';

        $queryBuilder->andWhere(
          $queryBuilder->expr()->like('LOWER('.$field.')', $queryBuilder->expr()->literal('%'.$name.'%'))
        )->orderBy('e.sort', 'ASC');


        $this->request->query->remove('search');

        if (isset($joins[$mainAlias])) {
            foreach ($joins[$mainAlias] as $join) {
                if ($join->getAlias() === '_toSend') { 
                    $queryBuilder->andWhere($queryBuilder->expr()->eq('_toSend.value', $user->getId()));
                }
            }
        }
    }
}
