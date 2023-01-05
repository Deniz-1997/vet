<?php

namespace App\Entity\Reference\Vaccine;

use App\Traits\ORMTraits\OrmDeletedTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use App\Entity\Reference\Animal\Animal;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use OpenApi\Annotations as SWG;
use App\Entity\Reference\Vaccine\VaccineSeries;
use App\Entity\User\User;
use App\Entity\Reference\Station;

/**
 * @ORM\Table(name="vaccination", schema="reference")
 * @ORM\Entity(repositoryClass="App\Repository\Vaccine\VaccinationRepository")
 */
class Vaccination
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait;

    /**
     * @var ArrayCollection $animals
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Animal\Animal", inversedBy="vaccinations", cascade={"persist"})
     * @ORM\JoinTable(name="vaccination.vaccination_animal",
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="animal_id", referencedColumnName="id")
     *      })
     *
     * @Assert\NotBlank()
     */
    private $animals;

    /**
     * @var ArrayCollection $vaccineSeries
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Vaccine\VaccineSeries", inversedBy="vaccinations", cascade={"persist"})
     *
     * @ORM\JoinTable(name="vaccination.vaccination_vaccine_series",
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="vaccine_series_id", referencedColumnName="id")
     *      })
     *
     * @Assert\NotNull(message = "choice.not_null")
     * @Assert\Valid()
     * @Assert\Count(min="1",minMessage="vaccination.vaccine_series.is_null")
     */
    private $vaccineSeries;

    /**
     * @var DateTime $date
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank(message="vaccination.date.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Date(message="vaccination.date.is_not_date", groups={"Default", "vaccination_excel_import"})
     */
    private DateTime $date;

    /**
     * @var User $createdBy
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $createdBy;

    /**
     * @var Station $station
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Station")
     * @ORM\JoinColumn(name="station", referencedColumnName="id")
     *
     * @Assert\NotNull(message = "choice.not_null")
     */
    private $station;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="App\Entity\User\User",
     *      inversedBy="vaccinations"
     * )
     * @ORM\JoinTable(
     *      name="vaccination.vaccination_person",
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     *      }
     * )
     * @Assert\NotNull(message = "choice.not_null")
     * @Assert\Count(min="1", minMessage="vaccination.people.no_doctor")
     * @Assert\Count(min="1", minMessage="vaccination.people.import_no_doctor", groups={"vaccination_excel_import"})
     */
    private $people;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string| null
     */
    private ?string $doctor;

    public function __construct()
    {
        $this->animals = new ArrayCollection;
        $this->vaccineSeries = new ArrayCollection;
        $this->people = new ArrayCollection;
    }

    public function setVaccineSeries($vaccineSeries)
    {
        $this->vaccineSeries = $vaccineSeries;
    }

    public function getVaccineSeries()
    {
        return $this->vaccineSeries;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }


    public function getStation()
    {
        return $this->station;
    }


    public function setStation($station)
    {
        $this->station = $station;

        return $this;
    }

    /**
     * Get targetEntity="MartInfo\VeterinaryBundle\Entity\Auth\Person",
     */ 
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * Set targetEntity="MartInfo\VeterinaryBundle\Entity\Auth\Person",
     *
     * @return  self
     */ 
    public function setPeople($people)
    {
        $this->people = $people;

        return $this;
    }

    public function setAnimals($animals)
    {
        $this->animals = $animals;
    }

    public function getAnimals()
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal)
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->addVaccination($this);
        }
    }

    public function removeAnimal(Animal $animal): self
    {
        $this->animals->removeElement($animal);

        return $this;
    }

    public function addVaccineSeries(VaccineSeries $vaccineSeries): self
    {
        if (!$this->vaccineSeries->contains($vaccineSeries)) {
            $this->vaccineSeries[] = $vaccineSeries;
        }

        return $this;
    }

    public function removeVaccineSeries(VaccineSeries $vaccineSeries): self
    {
        $this->vaccineSeries->removeElement($vaccineSeries);

        return $this;
    }

    public function addPerson(User $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
        }

        return $this;
    }

    public function removePerson(User $person): self
    {
        $this->people->removeElement($person);

        return $this;
    }

    public function setDoctor(?string $doctor)
    {
        $this->doctor = $doctor;
        return $this;
    }

    public function getDoctor()
    {
        return $this->doctor;
    }
}
