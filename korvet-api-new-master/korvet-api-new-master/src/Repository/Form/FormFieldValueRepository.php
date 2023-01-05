<?php

namespace App\Repository\Form;

use App\Entity\Form\FormFieldValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormFieldValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormFieldValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormFieldValue[]    findAll()
 * @method FormFieldValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormFieldValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormFieldValue::class);
    }

    // /**
    //  * @return FormFieldValue[] Returns an array of FormFieldValue objects
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
    public function findOneBySomeField($value): ?FormFieldValue
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
