<?php

namespace App\Repository\Leaving;

use App\Entity\Leaving\LeavingIndexSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeavingIndexSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeavingIndexSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeavingIndexSearch[]    findAll()
 * @method LeavingIndexSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavingIndexSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeavingIndexSearch::class);
    }

    // /**
    //  * @return LeavingIndexSearch[] Returns an array of LeavingIndexSearch objects
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
    public function findOneBySomeField($value): ?LeavingIndexSearch
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
