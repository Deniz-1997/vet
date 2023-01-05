<?php


namespace App\Repository\Reference\Appointment;


use App\Entity\Reference\Appointment\AppointmentTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentTemplate[]    findAll()
 * @method AppointmentTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentTemplate::class);
    }

    // /**
    //  * @return AppointmentTemplate[] Returns an array of AppointmentTemplate objects
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
    public function findOneBySomeField($value): ?AppointmentTemplate
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
