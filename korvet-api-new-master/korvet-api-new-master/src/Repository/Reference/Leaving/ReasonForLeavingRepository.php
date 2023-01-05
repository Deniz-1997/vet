<?php

namespace App\Repository\Reference\Leaving;

use App\Entity\Reference\Leaving\ReasonForLeaving;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReasonForLeaving|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReasonForLeaving|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReasonForLeaving[]    findAll()
 * @method ReasonForLeaving[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReasonForLeavingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReasonForLeaving::class);
    }

    // /**
    //  * @return ReasonForLeaving[] Returns an array of ReasonForLeaving objects
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
    public function findOneBySomeField($value): ?ReasonForLeaving
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
