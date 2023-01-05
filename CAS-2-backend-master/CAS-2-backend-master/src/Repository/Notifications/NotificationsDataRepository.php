<?php

namespace App\Repository\Notifications;

use App\Entity\Notifications\NotificationsData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NotificationsData|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationsData|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationsData[]    findAll()
 * @method NotificationsData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationsData::class);
    }

    // /**
    //  * @return NotificationsData[] Returns an array of NotificationsData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NotificationsData
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
