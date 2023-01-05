<?php

namespace App\Entity\Reference\Vaccine;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Reference\Countries;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Table(name="manufacturer", schema="reference")
 * @ORM\Entity(repositoryClass="App\Repository\Vaccine\ManufacturerRepository")
 */
class Manufacturer
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmNameTrait, OrmDeletedTrait;

     /**
      * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Countries")
     * @ORM\JoinColumn(nullable=false)
     * @var Countries
     */
    private $country;

    public function __toString()
    {
        return $this->name !== null ? $this->name : 'Manufacturer name not set.';
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
