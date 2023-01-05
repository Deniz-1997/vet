<?php

namespace App\Repository\Reference;

use App\Entity\Reference\VaccinationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VaccinationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VaccinationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VaccinationType[]    findAll()
 * @method VaccinationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VaccinationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VaccinationType::class);
    }

    // /**
    //  * @return VaccinationType[] Returns an array of VaccinationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VaccinationType
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
