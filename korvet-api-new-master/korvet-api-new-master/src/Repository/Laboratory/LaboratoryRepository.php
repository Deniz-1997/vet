<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\Laboratory;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Laboratory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Laboratory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Laboratory[]    findAll()
 * @method Laboratory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaboratoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Laboratory::class);
    }
}
