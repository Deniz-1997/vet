<?php

namespace App\Filter;

use App\Entity\Appointment\Appointment;
use App\Entity\Pet\Pet;
use Doctrine\ORM\QueryBuilder;

/**
 * Class PetAppointmentsFilter
 * @package App\Filter
 */
class PetAppointmentsFilter extends BaseFilter
{
    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === Pet::class && $this->request->query->get('filter') && isset($filtersRequest['appointments']);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $filter = $filtersRequest['appointments'];
        $queryBuilder
            ->leftJoin(
                Appointment::class,
                'a',
                'WITH',
                'a.pet = ' . $mainAlias . '.id'
            );
        if (isset($filter['>=date'])) {
            $queryBuilder->andWhere("a.date >= " . $filter['>=date']);
        }
        if (isset($filter['<=date'])) {
            $queryBuilder->andWhere("a.date <= " . $filter['<=date']);
        }

        unset($filtersRequest['appointments']);
    }
}
