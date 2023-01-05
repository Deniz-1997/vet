<?php

namespace App\Repository\Pet;

use App\Entity\Pet\IdentifierHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IdentifierHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdentifierHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdentifierHistory[]    findAll()
 * @method IdentifierHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentifierHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdentifierHistory::class);
    }

    // /**
    //  * @return IdentifierHistory[] Returns an array of IdentifierHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IdentifierHistory
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
