<?php

namespace App\Repository\Animal;

use App\Entity\Reference\Animal\AnimalLivingPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnimalLivingPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalLivingPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalLivingPlace[]    findAll()
 * @method AnimalLivingPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalLivingPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalLivingPlace::class);
    }
}