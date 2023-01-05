<?php

namespace App\Repository\Document;

use App\Entity\Document\DocumentHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentHistory[]    findAll()
 * @method DocumentHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentHistory::class);
    }

    // /**
    //  * @return DocumentHistory[] Returns an array of DocumentHistory objects
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
    public function findOneBySomeField($value): ?DocumentHistory
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
