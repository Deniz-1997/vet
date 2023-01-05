<?php

namespace App\Entity\Leaving;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(schema="leaving")
 * @ORM\Entity(repositoryClass="App\Repository\Leaving\LeavingTypeRepository")
 */
class LeavingType
{
    use OrmReferenceTrait;
}
