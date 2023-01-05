<?php

namespace App\Repository\Reference\Notifications;

use App\Entity\Reference\Notifications\ReferenceNotificationsChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReferenceNotificationsChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReferenceNotificationsChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReferenceNotificationsChannel[]    findAll()
 * @method ReferenceNotificationsChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenceNotificationsChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReferenceNotificationsChannel::class);
    }

    // /**
    //  * @return ReferenceNotificationsChannel[] Returns an array of ReferenceNotificationsChannel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReferenceNotificationsChannel
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
