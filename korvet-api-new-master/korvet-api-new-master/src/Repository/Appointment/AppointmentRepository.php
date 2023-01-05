<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\Appointment;
use App\Entity\Reference\Appointment\AppointmentProductItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return mixed
     */
    public function findBetweenDates(\DateTime $from, \DateTime $to)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin(AppointmentProductItem::class, 'pi', Join::WITH, 'pi.appointment = a.id')
            ->andWhere('pi.appointment IS NOT NULL')
            ->andWhere('a.date >= :from')
            ->andWhere('a.date <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

     /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return mixed
     */
    public function findBetweenEndDates(\DateTime $from, \DateTime $to)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin(AppointmentProductItem::class, 'pi', Join::WITH, 'pi.appointment = a.id')
            ->andWhere('pi.appointment IS NOT NULL')
            ->andWhere('a.dateEnd >= :from')
            ->andWhere('a.dateEnd <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Appointment[] Returns an array of Appointment objects
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
    public function findOneBySomeField($value): ?Appointment
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
