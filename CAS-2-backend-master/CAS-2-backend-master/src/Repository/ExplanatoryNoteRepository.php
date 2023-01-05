<?php

namespace App\Repository;

use App\Entity\ExplanatoryNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExplanatoryNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExplanatoryNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExplanatoryNote[]    findAll()
 * @method ExplanatoryNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExplanatoryNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExplanatoryNote::class);
    }

    // /**
    //  * @return ExplanatoryNote[] Returns an array of ExplanatoryNote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExplanatoryNote
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
