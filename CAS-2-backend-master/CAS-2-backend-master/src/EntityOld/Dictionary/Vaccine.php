<?php

namespace App\EntityOld\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\EntityOld\Dictionary\VaccineSeries;
use App\EntityOld\Dictionary\Disease;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\VaccineRepository")
 * @ORM\Table(name="dictionary.vaccine")
 * @UniqueEntity(fields={"name", "manufacturer"}, repositoryMethod="findInterferingVaccinesByNameAndManufacturer")
 */
class Vaccine
{
    use TimestampableEntity;

    /**
     * @var string $id
     *
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private string $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @var bool $invalid
     *
     * @ORM\Column(name="is_invalid", type="boolean", options={"default" = false})
     */
    private bool $invalid = false;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="App\EntityOld\Dictionary\Manufacturer",
     *      inversedBy="vaccines"
     * )
     * @Assert\NotBlank(groups={"edit_form"})
     */
    private $manufacturer;

        /**
        * @var ArrayCollection $diseases
        *
        * @ORM\ManyToMany(
        *      targetEntity="App\EntityOld\Dictionary\Disease", inversedBy="vaccines", cascade={"persist"})
        * @ORM\JoinTable(name="dictionary.vaccine_disease",
        *      inverseJoinColumns={
        *          @ORM\JoinColumn(name="disease_id", referencedColumnName="id")
        *      })
        */
    private $diseases;

    /**
     * @var int
     *
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

    public function setInvalid($invalid)
    {
        $this->invalid = $invalid;
    }

    public function isInvalid()
    {
        return $this->invalid;
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

    /**
     * Checks if two vaccinations have common manufacturer
     *
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback(groups={"subordination"})
     */
    public function validateManufacturer(ExecutionContextInterface $context)
    {
        $slaveManufacturer = $this->getManufacturer();
        $masterManufacturer = $this->getParent() ? $this->getParent()->getManufacturer() : null;
        $areManufacturersPresent = $slaveManufacturer && $masterManufacturer;

        if ($areManufacturersPresent && ($slaveManufacturer->getId() !== $masterManufacturer->getId())) {
            $context->buildViolation('vaccine.incorrect_manufacturer')->atPath('parent')->addViolation();
        }
    }

    /**
     * Checks if
     * vaccination is not parent for itself
     * and selected parent does not have its own parent
     * and selected parent is valid.
     * TODO: после переименования поля isInvalid перенести в трейт.
     *
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback(groups={"subordination"})
     */
    public function validateParent(ExecutionContextInterface $context)
    {
        $parent = $this->getParent();

        if ($parent) {
            if ($this->getId() === $parent->getId()) {
                $context->buildViolation('incorrect_parent.uroboros')->atPath('parent')->addViolation();
            }

            if ($parent->getParent()) {
                $context->buildViolation('incorrect_parent.p_is_not_orphan')->atPath('parent')->addViolation();
            }

            if ($parent->isInvalid()) {
                $context->buildViolation('incorrect_parent.p_is_not_valid')->atPath('parent')->addViolation();
            }
        }
    }
}
