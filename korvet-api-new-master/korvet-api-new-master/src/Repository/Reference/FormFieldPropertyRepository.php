<?php

namespace App\Repository\Reference;

use App\Entity\Reference\FormFieldProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormFieldProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormFieldProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormFieldProperty[]    findAll()
 * @method FormFieldProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormFieldPropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormFieldProperty::class);
    }

    // /**
    //  * @return FormFieldProperty[] Returns an array of FormFieldProperty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormFieldProperty
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
