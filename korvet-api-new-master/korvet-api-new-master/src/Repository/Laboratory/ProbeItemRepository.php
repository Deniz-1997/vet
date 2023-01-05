<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\ProbeItem;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProbeItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProbeItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProbeItem[]    findAll()
 * @method ProbeItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProbeItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProbeItem::class);
    }
}
