<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\ShelterRepository")
 * @ORM\Table("reference_shelter", schema="reference")
 */
class Shelter
{
    use OrmReferenceTrait , OrmSortTrait;
}
