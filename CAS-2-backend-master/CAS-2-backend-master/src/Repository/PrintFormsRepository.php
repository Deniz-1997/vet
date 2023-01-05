<?php

namespace App\Repository;

use App\Entity\PrintForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrintForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrintForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrintForm[]    findAll()
 * @method PrintForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrintFormsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrintForm::class);
    }

    public function findPrintForm($id): ?PrintForm
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->andWhere('p.deleted = false')
            ->andWhere('p.enabled = true')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
