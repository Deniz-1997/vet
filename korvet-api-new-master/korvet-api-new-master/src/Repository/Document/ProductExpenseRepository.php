<?php


namespace App\Repository\Document;

use App\Entity\Document\ProductExpense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductExpense|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductExpense|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductExpense[]    findAll()
 * @method ProductExpense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductExpense::class);
    }
}
