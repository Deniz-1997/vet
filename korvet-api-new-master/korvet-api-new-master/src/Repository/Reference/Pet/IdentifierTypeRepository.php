<?php

namespace App\Repository\Reference\Pet;

use App\Entity\Reference\Pet\IdentifierType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IdentifierType|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdentifierType|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdentifierType[]    findAll()
 * @method IdentifierType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentifierTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdentifierType::class);
    }

    // /**
    //  * @return IdentifierType[] Returns an array of IdentifierType objects
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
    public function findOneBySomeField($value): ?IdentifierType
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
