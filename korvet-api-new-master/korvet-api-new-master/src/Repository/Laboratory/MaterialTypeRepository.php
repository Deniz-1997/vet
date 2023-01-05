<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\MaterialType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method MaterialType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialType[]    findAll()
 * @method MaterialType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialType::class);
    }
}
