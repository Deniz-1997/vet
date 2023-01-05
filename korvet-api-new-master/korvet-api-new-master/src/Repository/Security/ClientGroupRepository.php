<?php

namespace App\Repository\Security;

use App\Entity\Security\ClientGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientGroup[]    findAll()
 * @method ClientGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientGroup::class);
    }

//    /**
//     * @return ClientGroup[] Returns an array of ClientGroup objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClientGroup
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
