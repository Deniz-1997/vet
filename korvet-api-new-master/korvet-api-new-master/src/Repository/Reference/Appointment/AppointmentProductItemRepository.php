<?php

namespace App\Repository\Reference\Appointment;

use App\Entity\Reference\Appointment\AppointmentProductItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentProductItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentProductItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentProductItem[]    findAll()
 * @method AppointmentProductItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentProductItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentProductItem::class);
    }

    // /**
    //  * @return AppointmentProductItem[] Returns an array of AppointmentProductItem objects
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
    public function findOneBySomeField($value): ?AppointmentProductItem
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
