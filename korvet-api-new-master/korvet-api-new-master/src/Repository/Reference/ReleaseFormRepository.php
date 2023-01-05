<?php

namespace App\Repository\Reference;

use App\Entity\Reference\ReleaseForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReleaseForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReleaseForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReleaseForm[]    findAll()
 * @method ReleaseForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReleaseFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReleaseForm::class);
    }

    // /**
    //  * @return ReleaseForm[] Returns an array of ReleaseForm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReleaseForm
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
