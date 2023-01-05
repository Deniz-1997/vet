<?php

namespace App\Repository\Document;

use App\Entity\Document\DocumentProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentProduct[]    findAll()
 * @method DocumentProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentProduct::class);
    }

    // /**
    //  * @return DocumentProduct[] Returns an array of DocumentProduct objects
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
    public function findOneBySomeField($value): ?DocumentProduct
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
