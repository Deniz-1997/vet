<?php

namespace App\Repository\ApiData;

use App\Entity\ApiData\ApiQueueRow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiQueueRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiQueueRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiQueueRow[]    findAll()
 * @method ApiQueueRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiQueueRowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiQueueRow::class);
    }
}
