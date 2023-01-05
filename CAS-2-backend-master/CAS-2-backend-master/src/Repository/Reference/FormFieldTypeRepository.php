<?php

namespace App\Repository\Reference;

use App\Entity\Reference\FormFieldType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormFieldType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormFieldType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormFieldType[]    findAll()
 * @method FormFieldType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormFieldTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormFieldType::class);
    }

    // /**
    //  * @return FormFieldType[] Returns an array of FormFieldType objects
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
    public function findOneBySomeField($value): ?FormFieldType
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
