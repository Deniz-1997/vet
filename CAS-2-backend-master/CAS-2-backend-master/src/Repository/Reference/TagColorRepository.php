<?php

namespace App\Repository\Reference;

use App\Entity\Reference\TagColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TagColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagColor[]    findAll()
 * @method TagColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagColor::class);
    }

    // /**
    //  * @return TagColor[] Returns an array of TagColor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagColor
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
