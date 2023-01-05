<?php

namespace App\Repository\Contractors;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Contractors\ContactType;

/**
 * @method ContactType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactType[]    findAll()
 * @method ContactType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactType::class);
    }

    /**
     * @param array $criteria
     * @return mixed
     */
    public function findInterferingContactTypesByCriteria(array $criteria)
    {
        return $this->createQueryBuilder('ContactType')
            ->where('lower(ContactType.id) = lower(:id)')
            ->setParameter('id', $criteria['id'])
            ->getQuery()
            ->execute();
    }
}
