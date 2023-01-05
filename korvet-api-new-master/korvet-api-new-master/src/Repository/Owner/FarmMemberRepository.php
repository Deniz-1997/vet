<?php

namespace App\Repository\Owner;

use App\Entity\Owner\FarmMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FarmMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method FarmMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method FarmMember[]    findAll()
 * @method FarmMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FarmMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FarmMember::class);
    }

    // /**
    //  * @return FarmMember[] Returns an array of FarmMember objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FarmMember
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
