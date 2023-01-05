<?php

namespace App\Entity\Reference;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\AnimalDeathRepository")
 * @ORM\Table("reference_animal_death", schema="reference")
 */
class AnimalDeath
{
    use OrmReferenceTrait, OrmSortTrait;
}
