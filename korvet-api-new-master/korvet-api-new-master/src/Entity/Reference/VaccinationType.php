<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\VaccinationTypeRepository")
 * @ORM\Table("reference_vaccination_type", schema="reference")
 */
class VaccinationType
{
    use OrmReferenceTrait, OrmSortTrait;
}
