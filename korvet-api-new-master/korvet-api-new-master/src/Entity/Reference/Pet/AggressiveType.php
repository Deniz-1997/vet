<?php

namespace App\Entity\Reference\Pet;

use App\Repository\Reference\Pet\AggressiveTypeRepository;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AggressiveTypeRepository::class)
 * @ORM\Table("reference_pet_aggressive_types", schema="reference")
 */
class AggressiveType
{
    use  OrmSortTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("default")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups("default")
     * @ORM\Column(type="boolean", options={"default"="false"})
     */
    private $deleted=false;

    /**
     * @Groups("default")
     * @ORM\Column(type="integer")
     */
    private $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->getDeleted();
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
