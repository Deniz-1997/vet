<?php

namespace App\Entity\Pet;

use App\Entity\Reference\Pet\IdentifierType;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pet\IdentifierHistoryRepository")
 * @ORM\Table("pets_identifiers_history", schema="pet")
 */
class IdentifierHistory
{
    use OrmIdTrait;

    /**
     * @var Pet
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet")
     */
    private $pet;

    /**
     * @var IdentifierType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Pet\IdentifierType")
     */
    private $identifierType;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @return Pet
     */
    public function getPet()
    {
        return $this->pet;
    }

    /**
     * @param Pet $pet
     */
    public function setPet(Pet $pet)
    {
        $this->pet = $pet;
    }

    /**
     * @return IdentifierType
     */
    public function getIdentifierType()
    {
        return $this->identifierType;
    }

    /**
     * @param IdentifierType $identifierType
     */
    public function setIdentifierType(IdentifierType $identifierType)
    {
        $this->identifierType = $identifierType;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
}
