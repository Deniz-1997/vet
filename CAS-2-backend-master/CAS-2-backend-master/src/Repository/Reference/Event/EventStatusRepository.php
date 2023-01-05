<?php

namespace App\Repository\Reference\Event;

use App\Entity\Reference\Event\EventStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventStatus[]    findAll()
 * @method EventStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventStatus::class);
    }

    // /**
    //  * @return EventStatus[] Returns an array of EventStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventStatus
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
