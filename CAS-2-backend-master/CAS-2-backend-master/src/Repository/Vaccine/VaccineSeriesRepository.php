<?php

namespace App\Repository\Vaccine;

use App\Entity\Reference\Vaccine\VaccineSeries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VaccineSeries|null find($id, $lockMode = null, $lockVersion = null)
 * @method VaccineSeries|null findOneBy(array $criteria, array $orderBy = null)
 * @method VaccineSeries[]    findAll()
 * @method VaccineSeries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VaccineSeriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VaccineSeries::class);
    }
}