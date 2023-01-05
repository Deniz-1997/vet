<?php

namespace App\EntityOld\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\EntityOld\Dictionary\Vaccine;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\ManufacturerRepository")
 * @ORM\Table(name="dictionary.manufacturer")
 * @UniqueEntity(fields={"name"}, repositoryMethod="findSameEntityByParams")
 */
class Manufacturer
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\EntityOld\Dictionary\Country",
     *     inversedBy="manufacturers"
     * )
     * @ORM\JoinColumn(name="country_iso", referencedColumnName="iso", nullable=false)
     * @Assert\NotBlank()
     */
    private $country;

    /** @ORM\Column(name="is_invalid", type="boolean", options={"default" = false}) */
    private $isInvalid = false;

    public function __toString()
    {
        return $this->name !== null ? $this->name : 'Manufacturer name not set.';
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Manufacturer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
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

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCountryIso()
    {
        return $this->country->getIso();
    }
}
