<?php

namespace App\Repository\Owner;

use App\Entity\Owner\FileMonitoredObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileMonitoredObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileMonitoredObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileMonitoredObject[]    findAll()
 * @method FileMonitoredObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileMonitoredObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileMonitoredObject::class);
    }
}
