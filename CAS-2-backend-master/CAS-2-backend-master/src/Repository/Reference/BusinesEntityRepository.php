<?php

namespace App\Repository\Reference;

use App\Entity\Reference\BusinesEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BusinesEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinesEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinesEntity[]    findAll()
 * @method BusinesEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinesEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusinesEntity::class);
    }

    // /**
    //  * @return BusinesEntity[] Returns an array of BusinesEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BusinesEntity
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
