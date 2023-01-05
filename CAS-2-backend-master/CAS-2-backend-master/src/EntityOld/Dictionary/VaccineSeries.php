<?php

namespace App\EntityOld\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\VaccineSeriesRepository")
 * @ORM\Table(name="dictionary.vaccine_series")
 * @UniqueEntity(
 *     fields={"serialNumber", "vaccine", "produced", "expires"},
 *     repositoryMethod="findInterferingSeries"
 * )
 */
class VaccineSeries
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
    private $id;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="App\EntityOld\Dictionary\VaccineSeries",
     *      inversedBy="children"
     * )
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *
     * @Serializer\Exclude
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\EntityOld\Dictionary\VaccineSeries", mappedBy="parent")
     *
     * @Serializer\Exclude
     */
    private $children;

    /**
     * @ORM\Column(type="string", name="serial_number", length=255, nullable=false)
     * @Assert\NotBlank(message="vaccine_series.serial_number.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Length(
     *     max="255",
     *     maxMessage="vaccine_series.serial_number.max_length",
     *     groups={"Default", "vaccination_excel_import"}
     * )
     */
    private $serialNumber; // todo a-z0-9

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="vaccine_series.produced.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Date(message="vaccine_series.produced.is_not_date", groups={"Default", "vaccination_excel_import"})
     */
    private $produced;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="vaccine_series.expires.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Date(message="vaccine_series.expires.is_not_date", groups={"Default", "vaccination_excel_import"})
     */
    private $expires;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="App\EntityOld\Dictionary\Vaccine",
     *      inversedBy="vaccineSeries"
     * )
     * @ORM\JoinColumn(name="vaccine_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull(message="vaccine_series.vaccine.is_null", groups={"Default", "vaccination_excel_import"})
     * @Serializer\Exclude
     * @var Vaccine
     */
    private $vaccine;


    /** @ORM\Column(name="is_invalid", type="boolean", options={"default" = false}) */
    private $isInvalid = false;

    function __construct()
    {
        $this->children = new ArrayCollection();
        $this->vaccinations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->format();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("format")
     */
    public function format()
    {
        return sprintf("%s, с %s по %s",
                       $this->getSerialNumber(),
                       $this->getProduced()->format('d.m.Y'),
                       $this->getExpires()->format('d.m.Y'));
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setParent(VaccineSeries $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getParentId()
    {
        $parentId = null;
        if ($this->parent) {
            $parentId = $this->parent->getId();
        }

        return $parentId;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function addChild(VaccineSeries $child)
    {
        $this->children->add($child);
        $child->setParent($this);
    }

    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    public function setProduced($produced)
    {
        $this->produced = $produced;
    }

    public function getProduced()
    {
        return $this->produced;
    }

    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param Vaccine $vaccine
     * @return $this
     */
    public function setVaccine(Vaccine $vaccine = null)
    {
        $this->vaccine = $vaccine;

        return $this;
    }

    /**
     * @return Vaccine
     */
    public function getVaccine()
    {
        return $this->vaccine;
    }

    public function getVaccineId()
    {
        return $this->vaccine->getId();
    }

    public function getVaccineName()
    {
        $name = '';

        $vaccine = $this->vaccine;
        if ($vaccine) {
            $name = $this->vaccine->getName();
        }

        return $name;
    }

    public function getManufacturerName()
    {
        $name = '';

        $manufacturer = $this->vaccine->getManufacturer();
        if ($manufacturer) {
            $name = $this->vaccine->getManufacturer()->getName();
        }

        return $name;
    }

    public function getManufacturerCountryName()
    {
        $name = '';

        $country = $this->vaccine->getManufacturer()->getCountry();
        if ($country) {
            $name = $country->getName();
        }

        return $name;
    }

    public function setIsInvalid($isInvalid)
    {
        $this->isInvalid = $isInvalid;
    }

    public function getIsInvalid()
    {
        return $this->isInvalid;
    }

    public function removeChild(VaccineSeries $children)
    {
        $this->children->removeElement($children);
    }

    public function getVaccinations()
    {
        return $this->vaccinations;
    }

    public function setVaccinations($vaccinations)
    {
        $this->vaccinations = $vaccinations;
    }
}
