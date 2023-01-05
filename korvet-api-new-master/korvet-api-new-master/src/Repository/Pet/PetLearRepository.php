<?php

namespace App\Repository\Pet;

use App\Entity\Reference\Pet\PetLear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PetLear|null find($id, $lockMode = null, $lockVersion = null)
 * @method PetLear|null findOneBy(array $criteria, array $orderBy = null)
 * @method PetLear[]    findAll()
 * @method PetLear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetLearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PetLear::class);
    }
}
