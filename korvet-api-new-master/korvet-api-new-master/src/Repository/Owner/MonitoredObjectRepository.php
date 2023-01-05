<?php

namespace App\Repository\Owner;

use App\Entity\Owner\MonitoredObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MonitoredObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonitoredObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonitoredObject[]    findAll()
 * @method MonitoredObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonitoredObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonitoredObject::class);
    }

    // /**
    //  * @return MonitoredObject[] Returns an array of MonitoredObject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MonitoredObject
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
