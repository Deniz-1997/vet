<?php

namespace App\Entity\Reference\Location;

use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Location\PathRepository")
 * @ORM\Table(schema="reference", name="path")
 */
class Path
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait;

    const PSQL_CENTER_CALC_TYPE = 'polygon';

    /**
     * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Location\Location", inversedBy="paths")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=false)
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
    private $data;

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
