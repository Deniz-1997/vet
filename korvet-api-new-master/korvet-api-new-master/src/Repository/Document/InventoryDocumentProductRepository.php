<?php

namespace App\Repository\Document;

use App\Entity\Document\InventoryDocumentProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InventoryDocumentProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventoryDocumentProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventoryDocumentProduct[]    findAll()
 * @method InventoryDocumentProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoryDocumentProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryDocumentProduct::class);
    }
}
