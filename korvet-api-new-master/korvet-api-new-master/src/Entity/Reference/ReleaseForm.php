<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table ("reference_release_form", schema="reference")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\ReleaseFormRepository")
 */
class ReleaseForm
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmSortTrait;
}
