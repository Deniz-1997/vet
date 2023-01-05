<?php

namespace App\Entity\Reference\Leaving;

use App\Repository\Reference\Leaving\ReasonForLeavingRepository;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Leaving\ReasonForLeavingRepository")
 * @ORM\Table ("reason_for_leaving" , schema="reference")
 */
class ReasonForLeaving
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmSortTrait;

}
