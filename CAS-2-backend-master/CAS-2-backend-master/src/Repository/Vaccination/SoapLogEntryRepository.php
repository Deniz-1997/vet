<?php

namespace App\Repository\Vaccination;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Vaccination\SoapLogEntry;

/**
 * @method SoapLogEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoapLogEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoapLogEntry[]    findAll()
 * @method SoapLogEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoapLogEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoapLogEntry::class);
    }
}
