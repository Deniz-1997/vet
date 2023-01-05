<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\ResearchHistory;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ResearchHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchHistory[]    findAll()
 * @method ResearchHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchHistory::class);
    }
}
