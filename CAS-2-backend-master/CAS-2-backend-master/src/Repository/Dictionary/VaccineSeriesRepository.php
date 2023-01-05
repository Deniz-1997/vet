<?php

namespace App\Repository\Dictionary;

use Doctrine\ORM\EntityRepository;
use App\EntityOld\Dictionary\VaccineSeries;

/**
 * @method VaccineSeries|null find($id, $lockMode = null, $lockVersion = null)
 * @method VaccineSeries|null findOneBy(array $criteria, array $orderBy = null)
 * @method VaccineSeries[]    findAll()
 * @method VaccineSeries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VaccineSeriesRepository extends EntityRepository
{
}
