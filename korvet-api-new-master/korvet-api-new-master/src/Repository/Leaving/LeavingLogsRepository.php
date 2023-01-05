<?php

namespace App\Repository\Leaving;

use App\Entity\Appointment\AppointmentLogs;
use App\Entity\Leaving\LeavingLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeavingLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeavingLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeavingLogs[]    findAll()
 * @method LeavingLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavingLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeavingLogs::class);
    }

    // /**
    //  * @return LeavingLogs[] Returns an array of LeavingLogs objects
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
    public function findOneBySomeField($value): ?LeavingLogs
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
