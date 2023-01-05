<?php

namespace App\Repository\Leaving;

use App\Entity\Appointment\AppointmentType;
use App\Entity\Leaving\LeavingType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeavingType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeavingType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeavingType[]    findAll()
 * @method LeavingType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavingTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeavingType::class);
    }
}
