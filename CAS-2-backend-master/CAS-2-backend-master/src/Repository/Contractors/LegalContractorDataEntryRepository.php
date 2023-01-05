<?php

namespace App\Repository\Contractors;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Contractors\LegalContractorDataEntry;

/**
 * @method LegalContractorDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalContractorDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalContractorDataEntry[]    findAll()
 * @method LegalContractorDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalContractorDataEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalContractorDataEntry::class);
    }

    public function getListQueryBuilder()
    {
        $qb = $this->createQueryBuilder('lp')
            ->orderBy('lp.fullName', 'ASC');
        return $qb;
    }
}
