<?php

namespace App\Entity\Reference\Vaccine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Entity\Reference\Vaccine\Vaccine;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Vaccine\VaccineSeriesRepository")
 * @ORM\Table(schema="reference", name="vaccine_series")
 */
class VaccineSeries
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait;

    /**
     * @Groups ({"default"})
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\Reference\Vaccine\VaccineSeries",
     *      inversedBy="children"
     * )
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable="true")
     *
     * @Serializer\Exclude
     */
    private $parent;

    /**
     * @Groups ({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\Reference\Vaccine\VaccineSeries", mappedBy="parent")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable="true")
     *
     * @Serializer\Exclude
     */
    private $children;

    /**
     * @Groups ({"default"})
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
     * @Groups ({"default"})
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="vaccine_series.produced.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Date(message="vaccine_series.produced.is_not_date", groups={"Default", "vaccination_excel_import"})
     */
    private $produced;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="vaccine_series.expires.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Date(message="vaccine_series.expires.is_not_date", groups={"Default", "vaccination_excel_import"})
     */
    private $expires;

      /**
       * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Vaccine\Vaccine")
     * @ORM\JoinColumn(nullable=false)
     * @var Vaccine
     */
    private $vaccine;

    function __construct()
    {
        $this->children = new ArrayCollection();
        $this->vaccinations = new ArrayCollection();
    }

    public function setParent(VaccineSeries $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
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

    public function getVaccinations()
    {
        return $this->vaccinations;
    }

    public function setVaccinations($vaccinations)
    {
        $this->vaccinations = $vaccinations;
    }

    public function removeChild(VaccineSeries $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
}
