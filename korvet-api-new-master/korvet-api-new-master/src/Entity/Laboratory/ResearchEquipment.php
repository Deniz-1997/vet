<?php

namespace App\Entity\Laboratory;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (schema="laboratory")
 * @ORM\Entity(repositoryClass="App\Repository\Laboratory\ResearchEquipmentRepository")
 */
class ResearchEquipment
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmSortTrait;
}
