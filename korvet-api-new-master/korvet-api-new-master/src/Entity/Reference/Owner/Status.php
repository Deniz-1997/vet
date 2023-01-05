<?php

namespace App\Entity\Reference\Owner;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Owner\StatusRepository")
 * @ORM\Table("reference_owner_status", schema="reference")
 */
class Status
{
    use OrmSortTrait; use OrmReferenceTrait;

    /**
     * @var string
     *
     * @Groups({"default"})
     *
     * @ORM\Column(type="string")
     */
    private $color;

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color)
    {
        $this->color = $color;
    }
}
