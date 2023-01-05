<?php

namespace App\Repository;

use App\Entity\ImportExportFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImportExportFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportExportFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportExportFile[]    findAll()
 * @method ImportExportFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportExportFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportExportFile::class);
    }

    // /**
    //  * @return ImportExportFile[] Returns an array of ImportExportFile objects
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
    public function findOneBySomeField($value): ?ImportExportFile
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
