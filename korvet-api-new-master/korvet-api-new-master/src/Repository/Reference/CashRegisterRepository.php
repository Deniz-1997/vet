<?php

namespace App\Repository\Reference;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reference\CashRegister;

/**
 * @method CashRegister|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashRegister|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashRegister[]    findAll()
 * @method CashRegister[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashRegisterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashRegister::class);
    }

    /**
     * @param $id
     * @return CashRegister|null
     */
    public function findCashRegister($id)
    {
        return $this->findOneBy(['id' => $id, 'deleted' => false]);
    }
}
