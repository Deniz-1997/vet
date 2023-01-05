<?php

namespace App\Repository\Reference\Leaving;

use App\Entity\Reference\Appointment\AppointmentProductItem;
use App\Entity\Reference\Leaving\LeavingProductItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeavingProductItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeavingProductItem[]    findAll()
 * @method LeavingProductItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavingProductItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeavingProductItem::class);
    }

    // /**
    //  * @return LeavingProductItem[] Returns an array of LeavingProductItem objects
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
    public function findOneBySomeField($value): ?LeavingProductItem
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
