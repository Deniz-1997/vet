<?php

namespace App\EntityOld\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\BreedRepository")
 * @ORM\Table(name="dictionary.breed")
 * @UniqueEntity(fields={"name", "kind"}, repositoryMethod="findInterferingBreedsByNameAndKind")
 */
class Breed
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="name", length=255, nullable=false)
     * @Assert\NotBlank(message="breed.name.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Length(max="255", maxMessage="breed.name.max_length", groups={"Default", "vaccination_excel_import"})
     */
    private $name;

    /** @ORM\Column(name="is_invalid", type="boolean", options={"default" = false}) */
    private $isInvalid = false;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setIsInvalid($isInvalid)
    {
        $this->isInvalid = $isInvalid;
    }

    public function getIsInvalid()
    {
        return $this->isInvalid;
    }
}
