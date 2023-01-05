<?php

namespace App\Entity\Reference;

use App\Repository\ReasonRetiringRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReasonRetiringRepository::class)
 * @ORM\Table ("reference_reason_retiring", schema="reference")
 */
class ReasonRetiring
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;
}
