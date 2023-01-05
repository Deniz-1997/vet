<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\Research;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Research|null find($id, $lockMode = null, $lockVersion = null)
 * @method Research|null findOneBy(array $criteria, array $orderBy = null)
 * @method Research[]    findAll()
 * @method Research[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Research::class);
    }
}
