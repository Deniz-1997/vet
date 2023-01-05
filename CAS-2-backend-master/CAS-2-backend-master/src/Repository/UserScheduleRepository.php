<?php


namespace App\Repository;

use App\Entity\UserSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSchedule[]    findAll()
 * @method UserSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserScheduleRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSchedule::class);
    }

}
