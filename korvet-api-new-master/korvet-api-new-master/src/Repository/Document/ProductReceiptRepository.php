<?php


namespace App\Repository\Document;

use App\Entity\Document\ProductReceipt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductReceipt|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductReceipt|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductReceipt[]    findAll()
 * @method ProductReceipt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductReceiptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductReceipt::class);
    }
}
