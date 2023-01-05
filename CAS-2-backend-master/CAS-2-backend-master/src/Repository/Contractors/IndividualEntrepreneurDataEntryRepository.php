<?php

namespace App\Repository\Contractors;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Contractors\IndividualEntrepreneurDataEntry;

/**
 * @method IndividualEntrepreneurDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndividualEntrepreneurDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndividualEntrepreneurDataEntry[]    findAll()
 * @method IndividualEntrepreneurDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndividualEntrepreneurDataEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndividualEntrepreneurDataEntry::class);
    }

    public function getListQueryBuilder()
    {
        $qb = $this->createQueryBuilder('iede')
            ->orderBy('iede.surname', 'ASC');
        return $qb;
    }

    public function findInterferingIndividualEntrepreneurDataEntriesUnique(array $criteria)
    {
        return $this->createQueryBuilder('iede')
            ->andWhere('iede.passportSerial = :passportSerial')
            ->andWhere('iede.passportNumber = :passportNumber')
            ->setParameters([
                'passportSerial' => $criteria['passportSerial'],
                'passportNumber' => $criteria['passportNumber'],
            ])
            ->getQuery()->execute();
    }
}
