<?php

namespace App\Entity\Reference;

use App\Repository\Reference\DiseaseRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Reference\Vaccine\Vaccine;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DiseaseRepository::class)
 * @ORM\Table ("reference_disease", schema="reference")
 */
class Disease
{
    use OrmIdTrait, OrmExternalIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @Groups ({"default"})
     * @var ArrayCollection
     * @ORM\ManyToMany(
     *      targetEntity="App\Entity\Reference\Vaccine\Vaccine",
     *      mappedBy="diseases",
     *      cascade={"persist"},
     * )
     */
    private $vaccines;

    public function __construct()
    {
        $this->vaccines = new ArrayCollection();
        $this->kindDiseaseRelations = new ArrayCollection();
    }

    public function setVaccines($vaccines)
    {
        $this->vaccines = $vaccines;
    }

    public function getVaccines()
    {
        return $this->vaccines;
    }

    public function setIsInvalid($isInvalid)
    {
        $this->isInvalid = $isInvalid;
    }

    public function addVaccine(Vaccine $vaccine): self
    {
        if (!$this->vaccines->contains($vaccine)) {
            $this->vaccines[] = $vaccine;
            $vaccine->addDisease($this);
        }

        return $this;
    }

    public function removeVaccine(Vaccine $vaccine): self
    {
        if ($this->vaccines->removeElement($vaccine)) {
            $vaccine->removeDisease($this);
        }

        return $this;
    }

}
