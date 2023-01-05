<?php

namespace App\Entity\Reference;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\TagFormRepository")
 * @ORM\Table("reference_tag_forms", schema="reference")
 */
class TagForm
{
    use OrmSortTrait; use OrmReferenceTrait;
}
