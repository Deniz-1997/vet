<?php

namespace App\Repository\Reference;

use App\Entity\Reference\CategoryNomenclature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryNomenclature|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryNomenclature|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryNomenclature[]    findAll()
 * @method CategoryNomenclature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryNomenclatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryNomenclature::class);
    }

    // /**
    //  * @return CategoryNomenclature[] Returns an array of CategoryNomenclature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryNomenclature
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
