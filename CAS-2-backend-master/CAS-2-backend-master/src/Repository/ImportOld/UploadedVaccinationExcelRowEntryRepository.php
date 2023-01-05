<?php

namespace App\Repository\ImportOld;

use Doctrine\ORM\EntityRepository;

class UploadedVaccinationExcelRowEntryRepository extends EntityRepository
{
    public function findRowForImportFromUploadedExcelFile(UploadedVaccinationExcelFileEntry $uploadedExcelFile)
    {
        $qb = $this->createQueryBuilder('u') // TODO возможно можно убрать фильтр по вакцинации
                   ->where('(u.excelFile = :excel) AND (u.statusCode = :status) AND (u.vaccination IS NULL)')
                   ->setParameter('excel', $uploadedExcelFile->getId())
                   ->setParameter('status', UploadedVaccinationExcelRowEntry::STATUS_PENDING)
                   ->orderBy('u.rowNumber', 'ASC')
                   ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
