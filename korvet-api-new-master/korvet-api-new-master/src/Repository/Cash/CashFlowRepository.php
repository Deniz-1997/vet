<?php

namespace App\Repository\Cash;

use App\Entity\Cash\CashFlow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Shift;

/**
 * @method CashFlow|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashFlow|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashFlow[]    findAll()
 * @method CashFlow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashFlowRepository extends ServiceEntityRepository
{
    /**
     * CashReceiptRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashFlow::class);
    }

    /**
     * @param int $id
     * @return CashFlow|null
     */
    public function findCashFlow(int $id): ?CashFlow
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
