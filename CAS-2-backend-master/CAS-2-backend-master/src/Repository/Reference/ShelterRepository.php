<?php

namespace App\Repository\Reference;

use App\Entity\Reference\Shelter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shelter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shelter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shelter[]    findAll()
 * @method Shelter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShelterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shelter::class);
    }

    // /**
    //  * @return Shelter[] Returns an array of Shelter objects
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
    public function findOneBySomeField($value): ?Shelter
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
