<?php

namespace App\EntityOld\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="action",
 *     schema="dictionary"
 * )
 */
class Action
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="additional_name", type="string", length=255, nullable=false)
     * @Assert\Length(max="255")
     * @var string
     */
    private $additionalName;

    /**
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Dictionary\KindDiseaseRelation", inversedBy="actions")
     * @ORM\JoinColumn(name="kind_disease_relation_id", nullable=false)
     * @Assert\NotBlank()
     * @var KindDiseaseRelation
     */
    private $kindDiseaseRelation;

    /**
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Dictionary\ActionKind", inversedBy="actions")
     * @ORM\JoinColumn(name="action_kind_id", nullable=false)
     * @Assert\NotBlank()
     * @var ActionKind
     */
    private $actionKind;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Action
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalName()
    {
        return $this->additionalName;
    }

    /**
     * @param string $additionalName
     * @return Action
     */
    public function setAdditionalName($additionalName)
    {
        $this->additionalName = $additionalName;
        return $this;
    }

    /**
     * @return KindDiseaseRelation
     */
    public function getKindDiseaseRelation()
    {
        return $this->kindDiseaseRelation;
    }

    /**
     * @param KindDiseaseRelation $kindDiseaseRelation
     * @return Action
     */
    public function setKindDiseaseRelation($kindDiseaseRelation)
    {
        $this->kindDiseaseRelation = $kindDiseaseRelation;
        return $this;
    }

    /**
     * @return ActionKind
     */
    public function getActionKind()
    {
        return $this->actionKind;
    }

    /**
     * @param ActionKind $actionKind
     * @return Action
     */
    public function setActionKind($actionKind)
    {
        $this->actionKind = $actionKind;
        return $this;
    }

    public function __toString()
    {
        return sprintf('%s - %s %s', $this->kindDiseaseRelation->getAnimalKind()->getName(),
            $this->kindDiseaseRelation->getDisease()->getName(),
            (is_null($this->additionalName) || strlen($this->additionalName) === 0) ?
                '' : sprintf('(%s)', $this->additionalName)
        );
    }

}
