<?php

namespace App\Repository\Dictionary;

use Doctrine\ORM\EntityRepository;
use App\EntityOld\Dictionary\Vaccine;

/**
 * @method Vaccine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vaccine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vaccine[]    findAll()
 * @method Vaccine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VaccineRepository extends EntityRepository
{
}
