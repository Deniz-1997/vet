<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\ResearchEquipment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ResearchEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchEquipment[]    findAll()
 * @method ResearchEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchEquipment::class);
    }
}
