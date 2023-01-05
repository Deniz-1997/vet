<?php


namespace App\Repository\Reference;

use App\Entity\Reference\TemplateReferenceValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateReferenceValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateReferenceValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateReferenceValue[]    findAll()
 * @method TemplateReferenceValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateReferenceValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateReferenceValue::class);
    }

    // /**
    //  * @return TemplateReferenceValue[] Returns an array of TemplateReferenceValue objects
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
    public function findOneBySomeField($value): ?TemplateReferenceValue
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
