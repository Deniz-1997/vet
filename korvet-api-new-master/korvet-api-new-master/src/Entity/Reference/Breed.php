<?php

namespace App\Entity\Reference;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\BreedRepository")
 * @ORM\Table("reference_breeds", schema="reference")
 */
class Breed
{
    use OrmSortTrait, OrmReferenceTrait, OrmExternalIdTrait;

    /**
     * @var PetType Тип животного
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\PetType", fetch="EAGER")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     * @SWG\Property(description="Тип животного")
     * @Assert\NotBlank(message="breed.type.not_blank")
     */
    private $type;

    /**
     * @var bool
     * @Groups("default")
     * @SWG\Property(description="Удален", type="boolean", default=false)
     */
    private $isDeleted = false;

    /**
     * @return PetType
     */
    public function getType(): PetType
    {
        return $this->type;
    }

    /**
     * @param PetType $type
     * @return Breed
     */
    public function setType(PetType $type): Breed
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->deleted;
    }
}
