<?php

namespace App\Repository\Pet;

use App\Entity\Pet\Identifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Identifier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Identifier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Identifier[]    findAll()
 * @method Identifier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentifierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Identifier::class);
    }

    // /**
    //  * @return Identifier[] Returns an array of Identifier objects
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
    public function findOneBySomeField($value): ?Identifier
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
