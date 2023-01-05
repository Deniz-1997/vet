<?php

namespace App\Repository\Leaving;

use App\Entity\Leaving\Leaving;
use App\Entity\Reference\Leaving\LeavingProductItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Leaving|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leaving|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leaving[]    findAll()
 * @method Leaving[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leaving::class);
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return mixed
     */
    public function findBetweenDates(\DateTime $from, \DateTime $to)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin(LeavingProductItem::class, 'pi', Join::WITH, 'pi.leaving = a.id')
            ->andWhere('pi.leaving IS NOT NULL')
            ->andWhere('a.date >= :from')
            ->andWhere('a.date <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Leaving[] Returns an array of Leaving objects
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
    public function findOneBySomeField($value): ?Leaving
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
