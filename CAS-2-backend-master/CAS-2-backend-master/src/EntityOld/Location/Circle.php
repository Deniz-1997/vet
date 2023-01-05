<?php

namespace App\EntityOld\Location;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use App\EntityOld\Location\Location;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     schema="mart_info_geo",
 *     name="circles",
 *     indexes={
 *          @ORM\Index(name="circles_location_id_idx", columns={"location_id"})
 *      }
 * )
 */
class Circle
{
    use TimestampableEntity;

    const PSQL_CENTER_CALC_TYPE = 'circle';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Location\Location", inversedBy="circles")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @ORM\Column(type="integer", name="group_num", nullable=true)
     */
    private $groupNum;

    /**
     * @ORM\Column(type="integer", name="build_order", nullable=true)
     */
    private $buildOrder;

    /**
     * @ORM\Column(type="boolean", name="included", nullable=true)
     */
    private $included;

    /**
     * @ORM\Column(type="postgis_point", name="center", nullable=false)
     * @Assert\NotNull()
     */
    private $center;

    /**
     * @ORM\Column(type="float", name="radius", nullable=false)
     * @Assert\NotNull()
     */
    private $radius;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     * @return Circle
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroupNum()
    {
        return $this->groupNum;
    }

    /**
     * @param mixed $groupNum
     * @return Circle
     */
    public function setGroupNum($groupNum)
    {
        $this->groupNum = $groupNum;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuildOrder()
    {
        return $this->buildOrder;
    }

    /**
     * @param mixed $buildOrder
     * @return Circle
     */
    public function setBuildOrder($buildOrder)
    {
        $this->buildOrder = $buildOrder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncluded()
    {
        return $this->included;
    }

    /**
     * @param mixed $included
     * @return Circle
     */
    public function setIncluded($included)
    {
        $this->included = $included;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * @param array $center
     * @return Circle
     */
    public function setCenter(array $center)
    {
        $this->center = $center;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param mixed $radius
     * @return Circle
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
        return $this;
    }
}
