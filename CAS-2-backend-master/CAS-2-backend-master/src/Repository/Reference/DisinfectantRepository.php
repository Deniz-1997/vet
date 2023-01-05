<?php

namespace App\Repository\Reference;

use App\Entity\Reference\Disinfectant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DisinfectantRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method DisinfectantRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method DisinfectantRepository[]    findAll()
 * @method DisinfectantRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisinfectantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Disinfectant::class);
    }
}
