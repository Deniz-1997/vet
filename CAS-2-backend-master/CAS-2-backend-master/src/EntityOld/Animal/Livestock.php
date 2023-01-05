<?php

namespace App\EntityOld\Animal;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="animal.livestock")
 */
class Livestock
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="MartInfo\VeterinaryBundle\Entity\Structure\SupervisedObject",
     *      inversedBy="livestockData", cascade={"persist"}
     * )
     * @Assert\Valid
     */
    private $supervisedObject;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="MartInfo\VeterinaryBundle\Entity\Structure\Individual",
     *      inversedBy="livestockData", cascade={"persist"}
     * )
     * @Assert\Valid
     */
    private $individual;

    /**
     * @ORM\Column(type="string", nullable=false, name="livestock_type")
     */
    private $livestockType;

    /**
     * @ORM\Column(type="integer", nullable=false, name="qty")
     */
    private $qty;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Livestock
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSupervisedObject()
    {
        return $this->supervisedObject;
    }

    /**
     * @param mixed $supervisedObject
     * @return Livestock
     */
    public function setSupervisedObject($supervisedObject)
    {
        $this->supervisedObject = $supervisedObject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * @param mixed $individual
     * @return Livestock
     */
    public function setIndividual($individual)
    {
        $this->individual = $individual;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLivestockType()
    {
        return $this->livestockType;
    }

    /**
     * @param mixed $livestockType
     * @return Livestock
     */
    public function setLivestockType($livestockType)
    {
        $this->livestockType = $livestockType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param mixed $qty
     * @return Livestock
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
        return $this;
    }

}
