<?php

namespace App\Repository\Import;

use App\Entity\Import\UploadedVaccinationExcelFileEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UploadedVaccinationExcelFileEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedVaccinationExcelFileEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedVaccinationExcelFileEntry[]    findAll()
 * @method UploadedVaccinationExcelFileEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedVaccinationExcelFileEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UploadedVaccinationExcelFileEntry::class);
    }
}
