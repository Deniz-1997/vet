<?php

namespace App\Repository;

use App\Entity\CullingRegistrationFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CullingRegistrationFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CullingRegistrationFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CullingRegistrationFile[]    findAll()
 * @method CullingRegistrationFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CullingRegistrationFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CullingRegistrationFile::class);
    }

    // /**
    //  * @return CullingRegistrationFile[] Returns an array of CullingRegistrationFile objects
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
    public function findOneBySomeField($value): ?CullingRegistrationFile
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
