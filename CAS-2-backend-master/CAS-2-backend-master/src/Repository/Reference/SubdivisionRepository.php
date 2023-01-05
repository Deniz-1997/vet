<?php

namespace App\Repository\Reference;

use App\Entity\Reference\Subdivision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subdivision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subdivision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subdivision[]    findAll()
 * @method Subdivision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubdivisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subdivision::class);
    }
}
