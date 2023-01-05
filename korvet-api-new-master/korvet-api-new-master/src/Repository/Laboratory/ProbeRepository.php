<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\Probe;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Probe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Probe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Probe[]    findAll()
 * @method Probe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProbeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Probe::class);
    }
}
