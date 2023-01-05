<?php


namespace App\Repository\Reference;


use App\Entity\Reference\TemplateReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateReference[]    findAll()
 * @method TemplateReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateReference::class);
    }

    // /**
    //  * @return TemplateReference[] Returns an array of TemplateReference objects
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
    public function findOneBySomeField($value): ?TemplateReference
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
