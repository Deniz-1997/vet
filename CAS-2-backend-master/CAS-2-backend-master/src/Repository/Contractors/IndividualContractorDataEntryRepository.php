<?php

namespace App\Repository\Contractors;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Contractors\IndividualContractorDataEntry;

/**
 * @method IndividualContractorDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndividualContractorDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndividualContractorDataEntry[]    findAll()
 * @method IndividualContractorDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndividualContractorDataEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndividualContractorDataEntry::class);
    }

    public function getListQueryBuilder()
    {
        $qb = $this->createQueryBuilder('icde')
            ->orderBy('icde.surname', 'ASC');
        return $qb;
    }

    public function findInterferingIndividualContractorDataEntriesUnique(array $criteria)
    {
        return $this->createQueryBuilder('icde')
            ->andWhere('icde.passportSerial = :passportSerial')
            ->andWhere('icde.passportNumber = :passportNumber')
            ->setParameters([
                'passportSerial' => $criteria['passportSerial'],
                'passportNumber' => $criteria['passportNumber'],
            ])
            ->getQuery()->execute();
    }

    public function findInterferingSnilsIndividualContractorDataEntriesUnique(array $criteria)
    {
        return $this->createQueryBuilder('icde')
            ->andWhere('icde.snils = :snils')
            ->setParameter('snils', $criteria['snils'])
            ->getQuery()->execute();
    }

    public function findInterferingInnIndividualContractorDataEntriesUnique(array $criteria)
    {
        return $this->createQueryBuilder('icde')
            ->andWhere('icde.inn = :inn')
            ->setParameter('inn', $criteria['inn'])
            ->getQuery()->execute();
    }
}
