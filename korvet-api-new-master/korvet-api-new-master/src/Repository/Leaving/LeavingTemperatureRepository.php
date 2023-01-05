<?php

namespace App\Repository\Leaving;


use App\Entity\Leaving\LeavingTemperature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeavingTemperature|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeavingTemperature|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeavingTemperature[]    findAll()
 * @method LeavingTemperature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavingTemperatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeavingTemperature::class);
    }
}
