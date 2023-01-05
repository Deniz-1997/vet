<?php

namespace App\Repository\Import;

use App\Entity\Import\UploadedVaccinationExcelRowEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UploadedVaccinationExcelRowEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedVaccinationExcelRowEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedVaccinationExcelRowEntry[]    findAll()
 * @method UploadedVaccinationExcelRowEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedVaccinationExcelRowEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UploadedVaccinationExcelRowEntry::class);
    }
}
