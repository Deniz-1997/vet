<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\SterilizationTypeRepository")
 * @ORM\Table("reference_sterilization_type", schema="reference")
 */
class SterilizationType
{
    use OrmReferenceTrait , OrmSortTrait;
}
