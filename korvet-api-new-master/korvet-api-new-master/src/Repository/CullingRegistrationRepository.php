<?php

namespace App\Repository;

use App\Entity\CullingRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CullingRegistration|null find($id, $lockMode = null, $lockVersion = null)
 * @method CullingRegistration|null findOneBy(array $criteria, array $orderBy = null)
 * @method CullingRegistration[]    findAll()
 * @method CullingRegistration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CullingRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CullingRegistration::class);
    }

    // /**
    //  * @return CullingRegistration[] Returns an array of CullingRegistration objects
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
    public function findOneBySomeField($value): ?CullingRegistration
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
