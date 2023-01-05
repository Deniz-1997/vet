<?php

namespace App\Repository\Cash;

use App\Entity\Cash\CashReceiptChanges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CashReceiptChanges|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashReceiptChanges|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashReceiptChanges[]    findAll()
 * @method CashReceiptChanges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashReceiptChangesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashReceiptChanges::class);
    }
}