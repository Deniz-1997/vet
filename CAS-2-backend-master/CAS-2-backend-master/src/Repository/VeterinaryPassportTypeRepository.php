<?php

namespace App\Repository;

use App\Entity\Reference\VeterinaryPassportType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VeterinaryPassportType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VeterinaryPassportType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VeterinaryPassportType[]    findAll()
 * @method VeterinaryPassportType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VeterinaryPassportTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VeterinaryPassportType::class);
    }
}
