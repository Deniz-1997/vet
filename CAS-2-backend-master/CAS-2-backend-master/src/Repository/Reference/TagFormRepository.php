<?php

namespace App\Repository\Reference;

use App\Entity\Reference\TagForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TagForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagForm[]    findAll()
 * @method TagForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagForm::class);
    }

    // /**
    //  * @return TagForm[] Returns an array of TagForm objects
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
    public function findOneBySomeField($value): ?TagForm
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
