<?php

namespace App\Repository\Reference\Pet;

use App\Entity\Reference\Pet\AggressiveType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AggressiveType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AggressiveType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AggressiveType[]    findAll()
 * @method AggressiveType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AggressiveTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AggressiveType::class);
    }

    // /**
    //  * @return AggressiveType[] Returns an array of AggressiveType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AggressiveType
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
