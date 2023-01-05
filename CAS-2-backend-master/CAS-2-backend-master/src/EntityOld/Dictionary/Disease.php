<?php

namespace App\EntityOld\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\EntityOld\Dictionary\Vaccine;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\DiseaseRepository")
 * @ORM\Table(name="dictionary.disease")
 * @UniqueEntity(fields={"name"}, repositoryMethod="findSameEntityByParams")
 */
class Disease
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /** @ORM\Column(type="string") */
    private $name;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="App\EntityOld\Dictionary\Vaccine",
     *      mappedBy="diseases",
     *      cascade={"persist"},
     * )
     */
    private $vaccines;

    /**
     * @ORM\OneToMany(
     *      targetEntity="KindDiseaseRelation",
     *      mappedBy="disease",
     * )
     */
    private $kindDiseaseRelations;

    /** @ORM\Column(name="is_invalid", type="boolean", options={"default" = false}) */
    private $isInvalid = false;

    public function __construct()
    {
        $this->vaccines = new ArrayCollection();
        $this->kindDiseaseRelations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
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

    public function getIsInvalid()
    {
        return $this->isInvalid;
    }

    public function addVaccine(Vaccine $vaccines)
    {
        $this->vaccines[] = $vaccines;

        return $this;
    }

    public function removeVaccine(Vaccine $vaccines)
    {
        $this->vaccines->removeElement($vaccines);
    }

    /**
     * Add kindDiseaseRelation
     *
     * @param KindDiseaseRelation $kindDiseaseRelation
     *
     * @return Disease
     */
    public function addKindDiseaseRelation(KindDiseaseRelation $kindDiseaseRelation)
    {
        $this->kindDiseaseRelations[] = $kindDiseaseRelation;

        return $this;
    }

    /**
     * Remove kindDiseaseRelation
     *
     * @param KindDiseaseRelation $kindDiseaseRelation
     */
    public function removeKindDiseaseRelation(KindDiseaseRelation $kindDiseaseRelation)
    {
        $this->kindDiseaseRelations->removeElement($kindDiseaseRelation);
    }

    /**
     * Get kindDiseaseRelations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKindDiseaseRelations()
    {
        return $this->kindDiseaseRelations;
    }
}
