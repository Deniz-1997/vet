<?php

namespace App\Entity\Reference;

use App\Repository\Reference\MeasurementUnitsRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table ("reference_measurement_units", schema="reference")
 * @ORM\Entity(repositoryClass=MeasurementUnitsRepository::class)
 */
class MeasurementUnits
{
    use OrmNameTrait, OrmSortTrait, OrmDeletedTrait, OrmIdTrait;

}
