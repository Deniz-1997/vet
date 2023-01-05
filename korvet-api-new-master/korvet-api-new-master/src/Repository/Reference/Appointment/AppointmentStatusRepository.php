<?php

namespace App\Repository\Reference\Appointment;

use App\Entity\Reference\Appointment\AppointmentStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentStatus[]    findAll()
 * @method AppointmentStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentStatus::class);
    }

    // /**
    //  * @return AppoinmentStatus[] Returns an array of AppoinmentStatus objects
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
    public function findOneBySomeField($value): ?AppoinmentStatus
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
