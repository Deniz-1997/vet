<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\AppointmentLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentLogs[]    findAll()
 * @method AppointmentLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentLogs::class);
    }

    // /**
    //  * @return AppointmentLogs[] Returns an array of AppointmentLogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AppointmentLogs
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
