<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\ResearchReason;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ResearchReason|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchReason|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchReason[]    findAll()
 * @method ResearchReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchReason::class);
    }
}
