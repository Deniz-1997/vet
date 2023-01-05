<?php

namespace App\Repository\Dictionary;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Dictionary\KindDiseaseRelation;

/**
 * @method KindDiseaseRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method KindDiseaseRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method KindDiseaseRelation[]    findAll()
 * @method KindDiseaseRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KindDiseaseRelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KindDiseaseRelation::class);
    }
}
