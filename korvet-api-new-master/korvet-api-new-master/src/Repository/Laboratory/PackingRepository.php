<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\Packing;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Packing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Packing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Packing[]    findAll()
 * @method Packing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Packing::class);
    }
}
