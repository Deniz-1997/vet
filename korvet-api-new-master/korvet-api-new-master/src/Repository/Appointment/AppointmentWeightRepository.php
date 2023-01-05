<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\AppointmentWeight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentWeight|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentWeight|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentWeight[]    findAll()
 * @method AppointmentWeight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentWeightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentWeight::class);
    }
}
