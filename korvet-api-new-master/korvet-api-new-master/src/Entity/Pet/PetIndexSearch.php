<?php

namespace App\Entity\Pet;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(schema="pet")
 * @ORM\Entity(repositoryClass="App\Repository\IndexSearch\PetIndexSearchRepository")
 */
class PetIndexSearch
{

    use OrmIdTrait;

    /**
     * @ORM\Column(type="tsvector", nullable=true)
     */
    private $index;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Pet\Pet", cascade={"persist", "remove"})
     */
    private $pet;

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
     * @return Pet|null
     */
    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    /**
     * @param Pet|null $pet
     * @return $this
     */
    public function setPet(?Pet $pet): self
    {
        $this->pet = $pet;

        return $this;
    }
}
