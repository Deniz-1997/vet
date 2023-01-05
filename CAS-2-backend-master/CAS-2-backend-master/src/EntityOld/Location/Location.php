<?php

namespace App\EntityOld\Location;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use App\EntityOld\Location\Circle;
use App\EntityOld\Location\Path;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Location\LocationRepository")
 * @ORM\Table(
 *     schema="mart_info_geo",
 *     name="locations",
 *     indexes={
 *          @ORM\Index(name="location_parent_id_idx", columns={"parent_id"})
 *      }
 * )
 */
class Location
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\EntityOld\Location\Location")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", name="name", length=200, nullable=true)
     * @Assert\Length(max="200", groups={"Default", "vaccination_excel_import"})
     */
    private $name;

    /**
     * @ORM\Column(name="address", type="string", nullable=true)
     * @Assert\Length(max="255", maxMessage="location.address_length_limit_exceeded",
     *     groups={"Default", "vaccination_excel_import"})
     */
    private $address;

    /**
     * @ORM\Column(name="fias_id", type="guid", nullable=true)
     * @var string
     */
    private $fiasId;

    /**
     * @ORM\Column(name="region_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $regionFiasId;

    /**
     * @ORM\Column(name="area_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $areaFiasId;

    /**
     * @ORM\Column(name="city_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $cityFiasId;

    /**
     * @ORM\Column(name="city_district_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $cityDistrictFiasId;

    /**
     * @ORM\Column(name="settlement_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $settlementFiasId;

    /**
     * @ORM\Column(name="street_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $streetFiasId;

    /**
     * @ORM\Column(name="house_fias_id", type="guid", nullable=true)
     * @var string
     */
    private $houseFiasId;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\EntityOld\Location\Path",
     *     mappedBy="location",
     *     cascade={"persist", "remove"})
     * @var ArrayCollection
     */
    private $paths;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\EntityOld\Location\Circle",
     *     mappedBy="location",
     *     cascade={"persist", "remove"}
     * )
     * @var ArrayCollection
     */
    private $circles;

    public function __construct()
    {
        $this->paths = new ArrayCollection();
        $this->circles = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function unsetId()
    {
        $this->id = null;
        return $this;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Add path
     *
     * @param Path $path
     *
     * @return Location
     */
    public function addPath(Path $path)
    {
        $path->setLocation($this);
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Remove path
     *
     * @param Path $path
     */
    public function removePath(Path $path)
    {
        $this->paths->removeElement($path);
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
     * @param Circle $circle
     * @return Location
     */
    public function addCircle(Circle $circle)
    {
        $circle->setLocation($this);
        $this->circles->add($circle);
        return $this;
    }

    /**
     * @param Circle $circle
     * @return Location
     */
    public function removeCircle(Circle $circle)
    {
        $this->circles->removeElement($circle);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCircles()
    {
        return $this->circles;
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
}
