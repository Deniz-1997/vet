<?php

namespace App\EntityOld\Vaccination;

use App\EntityOld\Auth\Person;
use App\EntityOld\Contractors\SupervisoryAuthority;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\EntityOld\Animal\Animal;
use App\EntityOld\Dictionary\VaccineSeries;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Table(name="vaccination.vaccination")
 * @ORM\Entity(repositoryClass="App\Repository\Vaccination\VaccinationRepository")
 * @UniqueEntity("id", message="Вакцинация с таким id уже существует.")
 */
class Vaccination
{
    use TimestampableEntity;

    /**
     * @var string $id
     *
     * @Groups({"default"})
     * @SWG\Property(type="guid", example="d3fec963-539e-4dfd-8abc-517673aaa7a8")
     *
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private string $id;

    /**
     * @var PersistentCollection $animals
     *
     * @ORM\ManyToMany(targetEntity="App\EntityOld\Animal\Animal", inversedBy="vaccinations", cascade={"persist"})
     * @ORM\JoinTable(name="vaccination.vaccination_animal",
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="animal_id", referencedColumnName="id")
     *      })
     *
     * @Assert\NotBlank()
     */
    private PersistentCollection $animals;

    /**
     * @var PersistentCollection $vaccineSeries
     *
     * @ORM\ManyToMany(targetEntity="App\EntityOld\Dictionary\VaccineSeries", inversedBy="vaccinations", cascade={"persist"})
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
    private PersistentCollection $vaccineSeries;

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
     * @var Person $createdBy
     *
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Auth\Person")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     * TODO: перевязать на fos_user
     */
    private Person $createdBy;

    /**
     * @var SupervisoryAuthority $station
     *
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Contractors\SupervisoryAuthority")
     * @ORM\JoinColumn(name="station", referencedColumnName="id")
     *
     * @Assert\NotNull(message = "choice.not_null")
     */
    private SupervisoryAuthority $station;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="App\EntityOld\Auth\Person",
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

    public function __construct()
    {
        $this->animals = new ArrayCollection;
        $this->vaccineSeries = new ArrayCollection;
        $this->people = new ArrayCollection;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @return SupervisoryAuthority
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * @param SupervisoryAuthority $station
     * @return $this
     */
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

    public function setAnimals(ArrayCollection $animals)
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
}
