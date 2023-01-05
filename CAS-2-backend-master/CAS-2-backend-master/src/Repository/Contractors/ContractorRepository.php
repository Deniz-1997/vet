<?php

namespace App\Repository\Contractors;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Contractors\Contractor;

/**
 * @method Contractor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contractor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contractor[]    findAll()
 * @method Contractor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contractor::class);
    }

    private function getCommonQueryBuilder()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c', 'i', 'ie', 'l')
            ->leftJoin('c.individualDataEntry', 'i')
            ->leftJoin('c.individualEntrepreneurDataEntry', 'ie')
            ->leftJoin('c.legalDataEntry', 'l');
        return $qb;
    }

    public function getListQueryBuilder()
    {
        $qb = $this->getCommonQueryBuilder();
        $qb->orderBy('c.createdAt', 'DESC');
        return $qb;
    }

    public function findOneByInn($inn)
    {
        $qb = $this->getCommonQueryBuilder();
        $qb->where($qb->expr()->orX(
            $qb->expr()->eq('i.inn', ':inn'),
            $qb->expr()->eq('ie.inn', ':inn'),
            $qb->expr()->eq('l.inn', ':inn')
        ));
        $qb->setParameter(':inn', $inn);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findOneByOgrn($ogrn)
    {
        $qb = $this->getCommonQueryBuilder();
        $qb->where($qb->expr()->orX(
            $qb->expr()->eq('ie.ogrnip', ':ogrn'),
            $qb->expr()->eq('l.ogrn', ':ogrn')
        ));
        $qb->setParameter(':ogrn', $ogrn);
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    public function alterSearch($searchPhrase)
    {
        $qb = $this->getCommonQueryBuilder();
        $qb->leftJoin('i.location', 'il')
            ->leftJoin('ie.legalLocation', 'iel')
            ->leftJoin('l.legalLocation', 'll');
        $qb->where($qb->expr()->orX(
            $qb->expr()->like('lower(c.name)', 'lower(:search)'),

            $qb->expr()->like('lower(il.address)', 'lower(:search)'),
            $qb->expr()->like('lower(iel.address)', 'lower(:search)'),
            $qb->expr()->like('lower(ll.address)', 'lower(:search)')
        ));
        $qb->setParameter(':search', '%' . $searchPhrase . '%');

        return $qb->getQuery()->getResult();
    }

    public function getListWithNoObjectsQueryBuilder()
    {
        $qb = $this->getCommonQueryBuilder();
        $qb->leftJoin('c.supervisedObjects', 'so')
            ->where('so.id IS NULL');
        return $qb;
    }
}
