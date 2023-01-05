<?php

namespace App\Repository\Owner;

use App\Entity\Owner\FileOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileOwner[]    findAll()
 * @method FileOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileOwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileOwner::class);
    }

    // /**
    //  * @return FileOwner[] Returns an array of FileOwner objects
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
    public function findOneBySomeField($value): ?FileOwner
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
