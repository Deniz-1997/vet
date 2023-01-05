<?php

namespace App\Entity\Reference\Location;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Entity\Reference\Location\Circle;
use App\Entity\Reference\Location\Path;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Location\LocationRepository")
 * @ORM\Table(schema="reference", name="location")
 */
class Location
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait;

    /**
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\Location\Location")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * @var Location
     */
    private $parent;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="address", type="string", nullable=true)
     * @Assert\Length(max="255", maxMessage="location.address_length_limit_exceeded",
     *     groups={"Default", "vaccination_excel_import"})
     */
    private $address;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="fias_id", type="guid", nullable=true)
     * @var string
     */
    private $fiasId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="region_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $regionFiasId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="area_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $areaFiasId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="city_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $cityFiasId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="city_district_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $cityDistrictFiasId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="settlement_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $settlementFiasId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="street_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $streetFiasId;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="house_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $houseFiasId;

    /**
     * @Groups ({"default"})
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Reference\Location\Path",
     *     mappedBy="location",
     *     cascade={"persist", "remove"})
     * @var ArrayCollection
     */
    private $paths;

    /**
     * @Groups ({"default"})
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Reference\Location\Circle",
     *     mappedBy="location",
     *     cascade={"persist", "remove"}
     * )
     * @var ArrayCollection
     */
    private $circles;

     /**
      * @Groups ({"default"})
     * @ORM\Column(nullable=true)
     * @var string
     */
    private $name;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $center;

    public function __construct()
    {
        $this->paths = new ArrayCollection();
        $this->circles = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * @param mixed $center
     */
    public function setCenter($center)
    {
        $this->center = $center;
    }




    /**
     * Get path
     *
     * @return Collection
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get path
     *
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Location
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }



    /**
     * @return Collection
     */
    public function getCircles()
    {
        return $this->circles;
    }

    public function setCircles($circle)
    {
        $this->circles = $circle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFiasId()
    {
        return $this->fiasId;
    }

    /**
     * @param string $fiasId
     * @return Location
     */
    public function setFiasId($fiasId)
    {
        $this->fiasId = $fiasId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegionFiasId()
    {
        return $this->regionFiasId;
    }

    /**
     * @param string $regionFiasId
     * @return Location
     */
    public function setRegionFiasId($regionFiasId)
    {
        $this->regionFiasId = $regionFiasId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAreaFiasId()
    {
        return $this->areaFiasId;
    }

    /**
     * @param string $areaFiasId
     * @return Location
     */
    public function setAreaFiasId($areaFiasId)
    {
        $this->areaFiasId = $areaFiasId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCityFiasId()
    {
        return $this->cityFiasId;
    }

    /**
     * @param string $cityFiasId
     * @return Location
     */
    public function setCityFiasId($cityFiasId)
    {
        $this->cityFiasId = $cityFiasId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCityDistrictFiasId()
    {
        return $this->cityDistrictFiasId;
    }

    /**
     * @param string $cityDistrictFiasId
     * @return Location
     */
    public function setCityDistrictFiasId($cityDistrictFiasId)
    {
        $this->cityDistrictFiasId = $cityDistrictFiasId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSettlementFiasId()
    {
        return $this->settlementFiasId;
    }

    /**
     * @param string $settlementFiasId
     * @return Location
     */
    public function setSettlementFiasId($settlementFiasId)
    {
        $this->settlementFiasId = $settlementFiasId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreetFiasId()
    {
        return $this->streetFiasId;
    }

    /**
     * @param string $streetFiasId
     * @return Location
     */
    public function setStreetFiasId($streetFiasId)
    {
        $this->streetFiasId = $streetFiasId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHouseFiasId()
    {
        return $this->houseFiasId;
    }

    /**
     * @param string $houseFiasId
     * @return Location
     */
    public function setHouseFiasId($houseFiasId)
    {
        $this->houseFiasId = $houseFiasId;
        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function addPath(Path $path): self
    {
        if (!$this->paths->contains($path)) {
            $this->paths[] = $path;
            $path->setLocation($this);
        }

        return $this;
    }

    public function removePath(Path $path): self
    {
        if ($this->paths->removeElement($path)) {
            // set the owning side to null (unless already changed)
            if ($path->getLocation() === $this) {
                $path->setLocation(null);
            }
        }

        return $this;
    }

    public function addCircle(Circle $circle): self
    {
        if (!$this->circles->contains($circle)) {
            $this->circles[] = $circle;
            $circle->setLocation($this);
        }

        return $this;
    }

    public function removeCircle(Circle $circle): self
    {
        if ($this->circles->removeElement($circle)) {
            // set the owning side to null (unless already changed)
            if ($circle->getLocation() === $this) {
                $circle->setLocation(null);
            }
        }

        return $this;
    }
}
