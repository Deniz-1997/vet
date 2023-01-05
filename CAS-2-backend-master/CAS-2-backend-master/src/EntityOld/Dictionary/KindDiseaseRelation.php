<?php

namespace App\EntityOld\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\KindDiseaseRelationRepository")
 * @UniqueEntity(
 *     fields={"animalKind", "disease"}
 * )
 * @ORM\Table(
 *     schema="dictionary",
 *     name="kind_disease_relation")
 */
class KindDiseaseRelation
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Kind",
     *      inversedBy="kindDiseaseRelations"
     * )
     * @ORM\JoinColumn(name="animal_kind_id", nullable=false, onDelete="CASCADE")
     */
    private $animalKind;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Disease",
     *      inversedBy="kindDiseaseRelations"
     * )
     * @ORM\JoinColumn(name="disease_id", nullable=false, onDelete="CASCADE")
     */
    private $disease;

    /**
     * @ORM\OneToMany(targetEntity="App\EntityOld\Dictionary\Action", mappedBy="kindDiseaseRelation")
     * @var ArrayCollection
     */
    private $actions;

    /**
     * KindDiseaseRelation constructor.
     */
    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set animalKind
     *
     * @param Kind $animalKind
     *
     * @return KindDiseaseRelation
     */
    public function setAnimalKind(Kind $animalKind = null)
    {
        $this->animalKind = $animalKind;

        return $this;
    }

    /**
     * Get animalKind
     *
     * @return Kind
     */
    public function getAnimalKind()
    {
        return $this->animalKind;
    }

    /**
     * Set disease
     *
     * @param Disease $disease
     *
     * @return KindDiseaseRelation
     */
    public function setDisease(Disease $disease = null)
    {
        $this->disease = $disease;

        return $this;
    }

    /**
     * Get disease
     *
     * @return Disease
     */
    public function getDisease()
    {
        return $this->disease;
    }

    /**
     * @return ArrayCollection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param Action $action
     * @return KindDiseaseRelation
     */
    public function addAction(Action $action)
    {
        $this->actions->add($action);
        return $this;
    }

    /**
     * @param Action $action
     * @return KindDiseaseRelation
     */
    public function removeAction(Action $action)
    {
        $this->actions->removeElement($action);
        return $this;
    }

    public function __toString()
    {
        return sprintf("%s - %s", $this->getAnimalKind()->getName(), $this->getDisease()->getName());
    }

}
