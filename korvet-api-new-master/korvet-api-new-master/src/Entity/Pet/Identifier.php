<?php

namespace App\Entity\Pet;

use App\Entity\Reference\Pet\IdentifierType;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pet\IdentifierRepository")
 * @ORM\Table("pets_identifiers", schema="pet")
 */
class Identifier
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var Pet Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", inversedBy="identifiers", cascade={"persist"})
     * @ORM\JoinColumn(name="pet_id", referencedColumnName="id")
     * @SWG\Property(description="Животное")
     * @Assert\NotBlank(message="pet_identifier.pet.not_blank")
     */
    private $pet;

    /**
     * @var IdentifierType Тип идентификатора
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Pet\IdentifierType", fetch="EAGER")
     * @SWG\Property(description="Тип идентификатора")
     * @Assert\NotBlank(message="pet_identifier.type.not_blank")
     */
    private $type;

    /**
     * @var string Значение
     * @Groups({"default"})
     * @ORM\Column(type="string")
     * @SWG\Property(description="Значение")
     * @Assert\NotBlank(message="pet_identifier.value.not_blank")
     */
    private $value;

    /**
     * @return Pet
     */
    public function getPet(): Pet
    {
        return $this->pet;
    }

    /**
     * @param Pet $pet
     * @return Identifier
     */
    public function setPet(Pet $pet): Identifier
    {
        $this->pet = $pet;
        return $this;
    }

    /**
     * @return IdentifierType
     */
    public function getType(): IdentifierType
    {
        return $this->type;
    }

    /**
     * @param IdentifierType $type
     */
    public function setType(IdentifierType $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
