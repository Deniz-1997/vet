<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\ProbeCorruptReason;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProbeCorruptReason|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProbeCorruptReason|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProbeCorruptReason[]    findAll()
 * @method ProbeCorruptReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProbeCorruptReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProbeCorruptReason::class);
    }
}
