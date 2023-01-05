<?php

namespace App\Repository;

use App\Entity\DeviceCashboxMobile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeviceCashboxMobile|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceCashboxMobile|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceCashboxMobile[]    findAll()
 * @method DeviceCashboxMobile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceCashboxMobileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceCashboxMobile::class);
    }

    // /**
    //  * @return DeviceCashboxMobile[] Returns an array of DeviceCashboxMobile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeviceCashboxMobile
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
