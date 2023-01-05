<?php

namespace App\Entity\Leaving;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(schema="leaving")
 * @ORM\Entity(repositoryClass="App\Repository\Leaving\LeavingIndexSearchRepository")
 */
class LeavingIndexSearch
{
    use OrmIdTrait;

    /**
     * @ORM\Column(type="tsvector", nullable=true)
     */
    private $index;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Leaving\Leaving", cascade={"persist", "remove"})
     */
    private $leaving;

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param $index
     * @return $this
     */
    public function setIndex($index): self
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return Leaving|null
     */
    public function getLeaving(): ?Leaving
    {
        return $this->leaving;
    }

    /**
     * @param Leaving|null $leaving
     * @return $this
     */
    public function setLeaving(?Leaving $leaving): self
    {
        $this->leaving = $leaving;

        return $this;
    }
}
