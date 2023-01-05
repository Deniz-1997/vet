<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\AppointmentIndexSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentIndexSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentIndexSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentIndexSearch[]    findAll()
 * @method AppointmentIndexSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentIndexSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentIndexSearch::class);
    }

    // /**
    //  * @return AppointmentIndexSearch[] Returns an array of AppointmentIndexSearch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AppointmentIndexSearch
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
