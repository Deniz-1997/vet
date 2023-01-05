<?php

namespace App\Repository\Cash;

use App\Entity\Cash\CashReceipt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Shift;

/**
 * @method CashReceipt|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashReceipt|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashReceipt[]    findAll()
 * @method CashReceipt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashReceiptRepository extends ServiceEntityRepository
{
    /**
     * CashReceiptRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashReceipt::class);
    }

    /**
     * @param int $id
     * @return CashReceipt|null
     */
    public function findCashReceipt(int $id): ?CashReceipt
    {
        return $this->findOneBy(['id' => $id, 'deleted' => false]);
    }

    /**
     * @param Shift $shift
     * @return array
     */
    public function findByShift(Shift $shift): array
    {
        return $this->findBy([
            'shift' => $shift,
            'deleted' => false,
        ]);
    }
}
