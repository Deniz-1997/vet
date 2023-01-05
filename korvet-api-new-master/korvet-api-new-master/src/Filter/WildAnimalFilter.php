<?php

namespace App\Filter;

use App\Entity\Contractor;
use App\Entity\CullingRegistration;
use App\Entity\Reference\Contractor\ContactPerson;
use App\Entity\WildAnimal;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SearchPetFilter
 * @package App\Filter
 */
class WildAnimalFilter extends BaseFilter
{
    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === WildAnimal::class && $this->request->query->get('filter');
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     * @throws \Exception
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $animalJoined = false;
        if (isset($filtersRequest['>=date'])) {
            $queryBuilder
                ->leftJoin(
                    CullingRegistration::class,
                    'c',
                    'WITH',
                    'c.wildAnimal = ' . $mainAlias . '.id'
                )
                ->andWhere($queryBuilder->expr()->between(
                    'c.date',
                    ':from',
                    ':till'
                ))
                ->setParameter('from', new \DateTime($filtersRequest['>=date']))
                ->setParameter('till', new \DateTime($filtersRequest['<=date']));
            unset($filtersRequest['<=date'], $filtersRequest['>=date']);
            $animalJoined = true;
        }

        if (isset($filtersRequest['contractorId'])) {
            if (!$animalJoined) {
                $queryBuilder
                    ->leftJoin(
                        CullingRegistration::class,
                        'c',
                        'WITH',
                        'c.wildAnimal = ' . $mainAlias . '.id'
                    );
            }
            $queryBuilder
                ->leftJoin(
                    ContactPerson::class,
                    'p',
                    'WITH',
                    'p.id = c.contactPerson'
                )->leftJoin(
                    Contractor::class,
                    'r',
                    'WITH',
                    'r.id = p.contractor'
                )
                ->andWhere('r.id = :contractorId')
                ->setParameter('contractorId', $filtersRequest['contractorId']);

            unset($filtersRequest['contractorId']);
        }
    }
}
