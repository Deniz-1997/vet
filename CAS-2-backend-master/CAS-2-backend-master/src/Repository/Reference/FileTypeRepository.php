<?php

namespace App\Repository\Reference;

use App\Entity\Reference\FileType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileType[]    findAll()
 * @method FileType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileType::class);
    }

    // /**
    //  * @return FileType[] Returns an array of FileType objects
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
    public function findOneBySomeField($value): ?FileType
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
