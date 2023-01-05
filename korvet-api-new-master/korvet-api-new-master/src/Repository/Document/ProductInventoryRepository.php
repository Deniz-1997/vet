<?php

namespace App\Repository\Document;

use App\Entity\Document\ProductInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductInventory[]    findAll()
 * @method ProductInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductInventory::class);
    }
}
