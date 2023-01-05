<?php

namespace App\Repository;

use App\Entity\Reference\ReasonRetiring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReasonRetiring|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReasonRetiring|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReasonRetiring[]    findAll()
 * @method ReasonRetiring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReasonRetiringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReasonRetiring::class);
    }

    // /**
    //  * @return ReasonRetiring[] Returns an array of ReasonRetiring objects
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
    public function findOneBySomeField($value): ?ReasonRetiring
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
