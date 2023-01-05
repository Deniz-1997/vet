<?php

namespace App\Entity\Reference;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\FileTypeRepository")
 * @ORM\Table("reference_file_types", schema="reference")
 */
class FileType
{
    use OrmSortTrait; use OrmReferenceTrait;
}
