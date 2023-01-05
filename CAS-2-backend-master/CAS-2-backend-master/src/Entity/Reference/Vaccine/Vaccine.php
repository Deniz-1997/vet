<?php

namespace App\Entity\Reference\Vaccine;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Entity\Reference\Vaccine\Manufacturer;
use App\Entity\Reference\Disease;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="vaccine", schema="reference")
 * @ORM\Entity(repositoryClass="App\Repository\Vaccine\VaccineRepository")
 */
class Vaccine
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmNameTrait, OrmDeletedTrait;

     /**
      * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Vaccine\Manufacturer")
     * @ORM\JoinColumn(nullable=true)
     * @var Manufacturer
     */
    private $manufacturer;

        /**
         * @Groups ({"default"})
        * @var ArrayCollection $diseases
        *
        * @ORM\ManyToMany(
        *      targetEntity="App\Entity\Reference\Disease", inversedBy="vaccines", cascade={"persist"})
        * @ORM\JoinTable(name="vaccination.vaccine_disease",
        *      inverseJoinColumns={
        *          @ORM\JoinColumn(name="disease_id", referencedColumnName="id")
        *      })
        * @ORM\JoinColumn(nullable=true)
        */
    private $diseases;

    /**
     * @var int
     * @Groups ({"default"})
     * @ORM\Column(name="activity_duration", type="integer", nullable=false, unique=false)
     *
     * @Assert\NotNull()
     * @Assert\Type("integer")
     * @Assert\Range(min=1, max=999)
     */
    private int $activityDuration;

    public function __construct()
    {
        $this->vaccineSeries = new ArrayCollection;
        $this->diseases = new ArrayCollection;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function setVaccineSeries($vaccineSeries)
    {
        $this->vaccineSeries = $vaccineSeries;
    }

    public function getVaccineSeries()
    {
        return $this->vaccineSeries;
    }

    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    public function getManufacturerId()
    {
        return $this->manufacturer->getId();
    }

    public function setDiseases($diseases)
    {
        $this->diseases = $diseases;
    }

    public function getDiseases()
    {
        return $this->diseases;
    }

    public function addVaccineSeries(VaccineSeries $vaccineSeries)
    {
        $this->vaccineSeries[] = $vaccineSeries;

        return $this;
    }

    public function removeVaccineSeries(VaccineSeries $vaccineSeries)
    {
        $this->vaccineSeries->removeElement($vaccineSeries);
    }

    public function addDisease(Disease $disease)
    {
        $this->diseases[] = $disease;

        return $this;
    }

    public function removeDisease(Disease $diseases)
    {
        $this->diseases->removeElement($diseases);
    }

    /**
     * @param int $activityDuration
     * @return Vaccine
     */
    public function setActivityDuration($activityDuration)
    {
        $this->activityDuration = $activityDuration;

        return $this;
    }

    /**
     * @return int
     */
    public function getActivityDuration()
    {
        return $this->activityDuration;
    }
}
