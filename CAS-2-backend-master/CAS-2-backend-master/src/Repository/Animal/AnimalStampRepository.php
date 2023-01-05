<?php

namespace App\Repository\Animal;

use App\Entity\Reference\Animal\AnimalStamp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnimalStamp|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalStamp|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalStamp[]    findAll()
 * @method AnimalStamp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalStampRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalStamp::class);
    }
}