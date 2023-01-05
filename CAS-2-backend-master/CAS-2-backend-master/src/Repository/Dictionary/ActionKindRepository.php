<?php

namespace App\Repository\Dictionary;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Dictionary\ActionKind;

/**
 * @method ActionKind|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionKind|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionKind[]    findAll()
 * @method ActionKind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionKindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionKind::class);
    }
}
