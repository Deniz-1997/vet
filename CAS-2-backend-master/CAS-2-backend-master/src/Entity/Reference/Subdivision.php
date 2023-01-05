<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Repository\Reference\SubdivisionRepository;

/**
 * @ORM\Entity(repositoryClass=SubdivisionRepository::class)
 * @ORM\Table("subdivision", schema="reference")
 */
class Subdivision
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmExternalIdTrait;

    /**
     * @var Station
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Station")
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id", nullable=false)
     */
    private Station $station;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"default"})
     */
    private bool $isPrivate;

    /**
     * @return Station
     */
    public function getStation(): Station
    {
        return $this->station;
    }

    /**
     * @param Station $station
     * @return $this
     */
    public function setStation(Station $station): self
    {
        $this->station = $station;
        return $this;
    }

     /**
     * @return bool
     */
    public function getIsPrivate(): bool
    {
        return $this->isPrivate;
    }

    /**
     * @param bool $station
     * @return $this
     */
    public function setIsPrivate(bool $isPrivate): self
    {
        $this->isPrivate = $isPrivate;
        return $this;
    }
}
