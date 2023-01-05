<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\ProbeSampling;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProbeSampling|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProbeSampling|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProbeSampling[]    findAll()
 * @method ProbeSampling[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProbeSamplingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProbeSampling::class);
    }
}
