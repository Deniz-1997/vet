<?php

namespace App\Repository\Notifications;

use App\Entity\Notifications\NotificationsToSend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NotificationsToSend|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationsToSend|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationsToSend[]    findAll()
 * @method NotificationsToSend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsToSendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationsToSend::class);
    }

    // /**
    //  * @return NotificationsToSend[] Returns an array of NotificationsToSend objects
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
    public function findOneBySomeField($value): ?NotificationsToSend
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
