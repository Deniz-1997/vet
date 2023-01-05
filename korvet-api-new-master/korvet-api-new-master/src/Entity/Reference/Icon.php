<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmCodeTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Action
 *
 * @ORM\Entity(repositoryClass="App\Repository\Reference\IconRepository")
 */
class Icon
{
    use OrmIdTrait, OrmNameTrait, OrmCodeTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var string
     * @Assert\NotBlank(message="icon.class.not_empty")
     * @Groups({"default"})
     * @ORM\Column(type="string")
     */
    private $class;

    /**
     * @var string
     *
     * @Groups({"default"})
     *
     * @ORM\Column(type="string", options={"default": ""})
     */
    private $subclass;

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
    /**
     * @return string
     */
    public function getSubclass(): string
    {
        return $this->subclass;
    }

    /**
     * @param string $subclass
     * @return $this
     */
    public function setSubclass(string $subclass): self
    {
        $this->subclass = $subclass;

        return $this;
    }
}
