<?php

namespace App\Repository\Reference\Leaving;

use App\Entity\Reference\Leaving\LeavingStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeavingStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeavingStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeavingStatus[]    findAll()
 * @method LeavingStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavingStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeavingStatus::class);
    }

    // /**
    //  * @return LeavingStatus[] Returns an array of LeavingStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LeavingStatus
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
