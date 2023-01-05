<?php

namespace App\Repository\Reference;

use App\Entity\Reference\FormTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormTemplate[]    findAll()
 * @method FormTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormTemplate::class);
    }

    // /**
    //  * @return FormTemplate[] Returns an array of FormTemplate objects
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
    public function findOneBySomeField($value): ?FormTemplate
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
