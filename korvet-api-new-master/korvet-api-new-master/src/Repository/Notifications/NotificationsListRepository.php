<?php

namespace App\Repository\Notifications;

use App\Entity\Notifications\NotificationsList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NotificationsList|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationsList|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationsList[]    findAll()
 * @method NotificationsList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationsList::class);
    }

    // /**
    //  * @return NotificationsList[] Returns an array of NotificationsList objects
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
    public function findOneBySomeField($value): ?NotificationsList
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
