<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\AppointmentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentType[]    findAll()
 * @method AppointmentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentType::class);
    }
}
