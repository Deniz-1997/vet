<?php
namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reference\CashRegister;
use App\Entity\Shift;

/**
 * @method Shift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shift[]    findAll()
 * @method Shift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftRepository extends ServiceEntityRepository
{
    /**
     * CashReceiptRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shift::class);
    }

    /**
     * @param int $id
     * @return Shift|null
     */
    public function findShift(int $id)
    {
        return $this->findOneBy(['id' => $id, 'deleted' => false]);
    }

    /**
     * @return Shift|null
     */
    public function findLastShift(CashRegister $cashRegister)
    {
        return $this->findOneBy(
            ['cashRegister' => $cashRegister, 'deleted' => false],
            ['id' => 'DESC']
        );
    }
}
