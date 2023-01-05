<?php

namespace App\Entity\ApiData;

use App\Repository\ApiData\ApiQueueRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Packages\DBAL\Types\ApiQueueStatusEnum;
use App\Entity\Reference\BusinesEntity;
use App\Entity\Reference\SupervisedObjects;
use App\Entity\User\User;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Reference\Station;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Reference\Subdivision;

/**
 * @ORM\Entity(repositoryClass=ApiQueueRepository::class)
 * @ORM\Table(schema="import")
 */
class ApiQueue
{
    use OrmIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmNameTrait;

    /**
     * @var BusinesEntity| null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\BusinesEntity")
     * @ORM\JoinColumn(nullable=true)
     */
    private $businessEntity;

     /**
     * @var SupervisedObjects|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\SupervisedObjects")
     * @ORM\JoinColumn(nullable=true)
     */
    private $supervisedObject;

     /**
     * @var Subdivision|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Subdivision")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subdivision;

     /**
     * @var Station| null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Station")
     * @ORM\JoinColumn(nullable=true)
     */
    private $station;

     /**
     * @var User
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hash;

     /**
     * @var ArrayCollection
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\ApiData\ApiQueueRow", mappedBy="apiQueue")
     */
    private $rows;

    public function __construct()
    {
        $this->rows = new ArrayCollection();
    }

    public function getBusinesEntity(): ?BusinesEntity
    {
        return $this->businessEntity;
    }

    public function setBusinesEntity(BusinesEntity $businessEntity): self
    {
        $this->businessEntity = $businessEntity;

        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(Station $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return  self
     */ 
    public function setHash(string $hash)
    {
        $this->hash = $hash;

        return $this;
    }
    
    public function addRow(ApiQueueRow $row): self
    {
        if ($this->rows->contains($row)) {
            $this->rows->add($row);
        }

        return $this;
    }

    public function removeRow(ApiQueueRow $row): self
    {
        $this->rows->removeElement($row);
        return $this;
    }

    /**
     * Get rows
     * 
     * @return ArrayCollection
     */
    public function getRows()
    {
        return $this->rows;
    }

    public function setRows($rows): self
    {
        $this->rows = $rows;
        return $this;
    }


    /**
     * Get null
     *
     * @return  SupervisedObjects|
     */ 
    public function getSupervisedObject()
    {
        return $this->supervisedObject;
    }

    /**
     * Set null
     *
     * @param  SupervisedObjects|  $supervisedObject  null
     *
     * @return  self
     */ 
    public function setSupervisedObject($supervisedObject)
    {
        $this->supervisedObject = $supervisedObject;

        return $this;
    }

    /**
     * @return  Subdivision|null
     */ 
    public function getSubdivision()
    {
        return $this->subdivision;
    }

    /**
     * @param  Subdivision|null  $subdivision
     *
     * @return  self
     */ 
    public function setSubdivision($subdivision)
    {
        $this->subdivision = $subdivision;

        return $this;
    }
}
