<?php

namespace App\Repository;

use App\Entity\WildAnimalFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WildAnimalFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method WildAnimalFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method WildAnimalFile[]    findAll()
 * @method WildAnimalFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WildAnimalFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WildAnimalFile::class);
    }
}
