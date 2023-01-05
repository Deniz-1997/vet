<?php

namespace App\Entity\Reference\Event;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Event\EventStatusRepository")
 */
class EventStatus
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
