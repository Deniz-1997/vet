<?php

namespace App\Entity\Reference\Pet;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Pet\IdentifierTypeRepository")
 * @ORM\Table("reference_pet_identifier_types", schema="reference")
 */
class IdentifierType
{
    use OrmSortTrait; use OrmReferenceTrait;
}
