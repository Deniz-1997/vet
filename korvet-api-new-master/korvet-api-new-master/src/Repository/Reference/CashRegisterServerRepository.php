<?php

namespace App\Repository\Reference;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reference\CashRegisterServer;

/**
 * @method CashRegisterServer|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashRegisterServer|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashRegisterServer[]    findAll()
 * @method CashRegisterServer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashRegisterServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashRegisterServer::class);
    }
}
