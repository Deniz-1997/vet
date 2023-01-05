<?php

namespace App\Repository\Reference;

use App\Entity\Reference\MeasurementUnits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MeasurementUnits|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeasurementUnits|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeasurementUnits[]    findAll()
 * @method MeasurementUnits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasurementUnitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeasurementUnits::class);
    }

    // /**
    //  * @return MeasurementUnits[] Returns an array of MeasurementUnits objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MeasurementUnits
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
