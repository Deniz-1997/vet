<?php

namespace App\Repository\Form;

use App\Entity\Form\FormTemplateField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormTemplateField|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormTemplateField|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormTemplateField[]    findAll()
 * @method FormTemplateField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormTemplateFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormTemplateField::class);
    }

    // /**
    //  * @return FormTemplateField[] Returns an array of FormTemplateField objects
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
    public function findOneBySomeField($value): ?FormTemplateField
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
