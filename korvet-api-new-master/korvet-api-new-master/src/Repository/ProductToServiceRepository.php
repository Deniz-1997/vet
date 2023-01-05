<?php

namespace App\Repository;

use App\Entity\ProductToService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductToService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductToService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductToService[]    findAll()
 * @method ProductToService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductToServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductToService::class);
    }

    // /**
    //  * @return ProductToService[] Returns an array of ProductToService objects
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
    public function findOneBySomeField($value): ?ProductToService
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
