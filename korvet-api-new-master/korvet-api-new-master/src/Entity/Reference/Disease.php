<?php

namespace App\Entity\Reference;

use App\Repository\Reference\DiseaseRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiseaseRepository::class)
 * @ORM\Table ("reference_disease", schema="reference")
 */
class Disease
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;
}
