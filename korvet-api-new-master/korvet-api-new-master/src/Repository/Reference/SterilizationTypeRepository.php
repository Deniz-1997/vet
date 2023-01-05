<?php

namespace App\Repository\Reference;

use App\Entity\Reference\SterilizationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SterilizationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SterilizationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SterilizationType[]    findAll()
 * @method SterilizationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SterilizationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SterilizationType::class);
    }

    // /**
    //  * @return SterilizationType[] Returns an array of SterilizationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SterilizationType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
