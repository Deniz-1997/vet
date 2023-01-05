<?php

namespace App\Entity\Reference\Owner;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Owner\LegalFormRepository")
 * @ORM\Table("reference_owner_legal_forms", schema="reference")
 */
class LegalForm
{
    use OrmSortTrait; use OrmReferenceTrait;
}
