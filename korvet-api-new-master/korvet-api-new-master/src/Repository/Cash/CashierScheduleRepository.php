<?php

namespace App\Repository\Cash;

use App\Entity\Cash\CashierSchedule;
use App\Entity\Reference\CashRegister;
use App\Interfaces\CashierUserInterface;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method CashierSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashierSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashierSchedule[]    findAll()
 * @method CashierSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashierScheduleRepository extends ServiceEntityRepository
{
    /**
     * CashReceiptRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashierSchedule::class);
    }

    /**
     * @param CashRegister $cashRegister
     * @param DateTime $dateTime
     * @return CashierSchedule[]
     */
    public function findCashierScheduleForDateTime(CashRegister $cashRegister, DateTime $dateTime): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.cashRegister', 'c')
            ->where('c.id = :cashRegisterId')
            ->andWhere('s.dateFrom <= :dateTime')
            ->andWhere('s.dateTo >= :dateTime')
            ->andWhere('s.deleted = false')
            ->andWhere('c.deleted = false')
            ->setParameter('cashRegisterId', $cashRegister->getId())
            ->setParameter('dateTime', $dateTime)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param CashRegister $cashRegister
     * @param CashierUserInterface $cashierUser
     * @return CashierSchedule
     * @throws NonUniqueResultException
     */
    public function findLastCashierScheduleForCashier(CashRegister $cashRegister, CashierUserInterface $cashierUser): CashierSchedule
    {
        return $this->createQueryBuilder('s')
            ->join('s.cashRegister', 'c')
            ->join('s.cashier', 'cashier')
            ->where('c.id = :cashRegisterId')
            ->andWhere('s.deleted = false')
            ->andWhere('c.deleted = false')
            ->andWhere('cashier.id = :cashierId')
            ->orderBy('s.id', 'ASC')
            ->setParameter('cashierId', $cashierUser->getId())
            ->setParameter('cashRegisterId', $cashRegister->getId())
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }


    /**
     * @param CashierUserInterface $cashierUser
     * @return ?CashierSchedule
     * @throws NonUniqueResultException
     */
    public function findCurrentCashierScheduleForCashier(CashierUserInterface $cashierUser): ?CashierSchedule
    {
        return $this->createQueryBuilder('s')
            ->join('s.cashRegister', 'c')
            ->join('s.cashier', 'cashier')
            ->andWhere('s.deleted = false')
            ->andWhere('c.deleted = false')
            ->andWhere('cashier.id = :cashierId')
            ->orderBy('s.id', 'DESC')
            ->setParameter('cashierId', $cashierUser->getId())
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
