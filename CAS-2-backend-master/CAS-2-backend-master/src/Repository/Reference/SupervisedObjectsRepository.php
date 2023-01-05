<?php

namespace App\Repository\Reference;

use App\Entity\Reference\SupervisedObjects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupervisedObjects|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupervisedObjects|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupervisedObjects[]    findAll()
 * @method SupervisedObjects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupervisedObjectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupervisedObjects::class);
    }

    // /**
    //  * @return SupervisedObjects[] Returns an array of SupervisedObjects objects
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
    public function findOneBySomeField($value): ?SupervisedObjects
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
