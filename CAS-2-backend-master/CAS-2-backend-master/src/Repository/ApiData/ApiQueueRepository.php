<?php

namespace App\Repository\ApiData;

use App\Entity\ApiData\ApiQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiQueue[]    findAll()
 * @method ApiQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiQueue::class);
    }

    // /**
    //  * @return ApiQueue[] Returns an array of ApiToken objects
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
    public function findOneBySomeField($value): ?ApiToken
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
