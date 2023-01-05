<?php

namespace App\Repository\Form;

use App\Entity\Form\FormFieldPropertyValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormFieldPropertyValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormFieldPropertyValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormFieldPropertyValue[]    findAll()
 * @method FormFieldPropertyValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormFieldPropertyValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormFieldPropertyValue::class);
    }

    // /**
    //  * @return FormFieldPropertyValue[] Returns an array of FormFieldPropertyValue objects
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
    public function findOneBySomeField($value): ?FormFieldPropertyValue
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
