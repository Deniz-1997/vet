<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\AppointmentTemperature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentTemperature|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentTemperature|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentTemperature[]    findAll()
 * @method AppointmentTemperature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentTemperatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentTemperature::class);
    }
}
