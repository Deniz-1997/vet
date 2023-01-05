<?php

namespace App\Entity\Reference\Location;

use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Reference\Location\Location;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Location\CircleRepository")
 * @ORM\Table(schema="reference", name="circle")
 */
class Circle
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait;

    const PSQL_CENTER_CALC_TYPE = 'circle';

    /**
     * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Location\Location", inversedBy="circles")
     * @ORM\JoinColumn(nullable=false)
     * @var Location
     */
    private $location;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="integer", name="group_num", nullable=true)
     */
    private $groupNum;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="integer", name="build_order", nullable=true)
     */
    private $buildOrder;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="boolean", name="included", nullable=true)
     */
    private $included;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotNull()
     */
    private $center;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="float", name="radius", nullable=false)
     * @Assert\NotNull()
     */
    private $radius;

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
     * @param string $center
     * @return Circle
     */
    public function setCenter(string $center)
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
