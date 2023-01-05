<?php

namespace App\Repository\Vaccination;

use App\Entity\OAuth\RefreshToken;
use App\EntityOld\Vaccination\Vaccination;
use App\Repository\AutowiredRepositoryTrait;
use App\Traits\IsolatedEntityManagerTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vaccination|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vaccination|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vaccination[]    findAll()
 * @method Vaccination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VaccinationRepository extends EntityRepository
{

}
