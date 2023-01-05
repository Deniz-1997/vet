<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Table ("reference_manufacturer", schema="reference")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\ManufacturerRepository")
 */
class Manufacturer
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var Countries|null Страна
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\Countries")
     * @ORM\JoinColumn(nullable=true)
     */
    private $countries;


    /**
     * @return Countries|null
     */
    public function getCountries(): ?Countries
    {
        return $this->countries;
    }


    /**
     * @param Countries|null $countries
     * @return $this
     */
    public function setCountries(?Countries $countries): self
    {
        $this->countries = $countries;
        return $this;
    }



}

