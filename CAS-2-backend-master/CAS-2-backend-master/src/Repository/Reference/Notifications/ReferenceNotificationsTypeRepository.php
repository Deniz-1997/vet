<?php

namespace App\Repository\Reference\Notifications;

use App\Entity\Reference\Notifications\ReferenceNotificationsType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReferenceNotificationsType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReferenceNotificationsType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReferenceNotificationsType[]    findAll()
 * @method ReferenceNotificationsType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenceNotificationsTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReferenceNotificationsType::class);
    }

    // /**
    //  * @return ReferenceNotificationsType[] Returns an array of ReferenceNotificationsType objects
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
    public function findOneBySomeField($value): ?ReferenceNotificationsType
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
