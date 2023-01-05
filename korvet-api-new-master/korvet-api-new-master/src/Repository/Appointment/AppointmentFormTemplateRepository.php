<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\AppointmentFormTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentFormTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentFormTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentFormTemplate[]    findAll()
 * @method AppointmentFormTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentFormTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentFormTemplate::class);
    }

    // /**
    //  * @return AppointmentFormTemplate[] Returns an array of AppointmentFormTemplate objects
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
    public function findOneBySomeField($value): ?AppointmentFormTemplate
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
