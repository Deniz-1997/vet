<?php

namespace App\EntityOld\Location;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     schema="mart_info_geo",
 *     name="paths",
 *     indexes={
 *          @ORM\Index(name="paths_location_id_idx", columns={"location_id"})
 *      }
 * )
 */
class Path
{
    use TimestampableEntity;

    const PSQL_CENTER_CALC_TYPE = 'polygon';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Location\Location", inversedBy="paths")
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
     * @ORM\Column(type="postgis_polygon", name="data", nullable=false)
     * @Assert\NotNull()
     */
    private $data;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     * @return Path
     *
     */
    public function setLocation($location)
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
     * @return Path
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
     * @return Path
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
     * @return Path
     */
    public function setIncluded($included)
    {
        $this->included = $included;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Path
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
}
