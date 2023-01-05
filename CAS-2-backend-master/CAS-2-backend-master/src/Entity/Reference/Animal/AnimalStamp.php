<?php

namespace App\Entity\Reference\Animal;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Reference\Animal\Animal;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Animal\AnimalStampRepository")
 * @ORM\Table(schema="animal", name="animal_stamps")
 */
class AnimalStamp
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait, OrmNameTrait;

    /**
     * @var \DateTime
     * @Groups ({"default"})
     * @ORM\Column(name="stamp_date", type="date", nullable=true)
     */
    private $stampDate;

    /**
     * @var boolean
     * @ORM\Column(name="is_current", type="boolean", nullable=false)
     */
    private $isCurrent = true;

     /**
      * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Animal\Animal")
     * @ORM\JoinColumn(nullable=false)
     * @var Animal
     */
    private $animal;
    
    /**
     * Set stampDate
     * @param \DateTime $stampDate
     * @return AnimalStamp
     */
    public function setStampDate($stampDate)
    {
        $this->stampDate = $stampDate;
        return $this;
    }

    /**
     * Get stampDate
     * @return \DateTime
     */
    public function getStampDate()
    {
        return $this->stampDate;
    }

    /**
     * Set isCurrent
     * @param boolean $isCurrent
     * @return AnimalStamp
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;
        return $this;
    }

    /**
     * Get isCurrent
     * @return boolean
     */
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * Set animal
     * @return \App\Entity\Reference\Animal\Animal|null
     */

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

}

