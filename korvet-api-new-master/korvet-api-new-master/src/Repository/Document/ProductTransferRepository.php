<?php

namespace App\Repository\Document;

use App\Entity\Document\ProductTransfer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductTransfer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductTransfer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductTransfer[]    findAll()
 * @method ProductTransfer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTransferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductTransfer::class);
    }

    // /**
    //  * @return ProductTransfer[] Returns an array of ProductTransfer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductTransfer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
