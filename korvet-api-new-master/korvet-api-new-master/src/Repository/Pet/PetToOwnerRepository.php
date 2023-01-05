<?php

namespace App\Repository\Pet;

use App\Entity\Pet\PetToOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PetToOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method PetToOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method PetToOwner[]    findAll()
 * @method PetToOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetToOwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PetToOwner::class);
    }

    // /**
    //  * @return PetToOwner[] Returns an array of PetToOwner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PetToOwner
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
