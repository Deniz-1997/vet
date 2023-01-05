<?php

namespace App\EntityOld\Animal;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\EntityOld\Animal\Animal;

/**
 * AnimalStamp
 *
 *
 * @ORM\Entity(repositoryClass="App\Repository\AnimalOld\AnimalStampRepository")
 * @ORM\Table(
 *      schema="animal",
 *      name="animal_stamps"
 * )
 */
class AnimalStamp
{
    use TimestampableEntity;

    /**
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     * @Assert\NotNull(message="animal_stamp.stamp_name_cannot_be_blank", groups={
     *     "Default",
     *     "animal_edit",
     *     "vaccination_excel_import"
     * })
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stamp_date", type="date", nullable=true)
     */
    private $stampDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_current", type="boolean", nullable=false)
     */
    private $isCurrent = true;

     /**
     * @var Animal
     *
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Animal\Animal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="animal_id", referencedColumnName="id")
     * })
     */
    private $animal;


    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Stamp Type
     *
     * @return AnimalStampType
     */
    public function getStampType()
    {
        return $this->stampType;
    }

    /**
     * Set stamp type
     *
     * @param AnimalStampType $stampType
     *
     * @return AnimalStamp
     */
    public function setStampType($stampType)
    {
        $this->stampType = $stampType;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AnimalStamp
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set stampDate
     *
     * @param \DateTime $stampDate
     *
     * @return AnimalStamp
     */
    public function setStampDate($stampDate)
    {
        $this->stampDate = $stampDate;

        return $this;
    }

    /**
     * Get stampDate
     *
     * @return \DateTime
     */
    public function getStampDate()
    {
        return $this->stampDate;
    }

    /**
     * Set isCurrent
     *
     * @param boolean $isCurrent
     *
     * @return AnimalStamp
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    /**
     * Get isCurrent
     *
     * @return boolean
     */
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * Set animal
     *
     * @param Animal $animal
     *
     * @return AnimalStamp
     */
    public function setAnimal(Animal $animal = null)
    {
        $this->animal = $animal;

        return $this;
    }

    /**
     * Get animal
     *
     * @return Animal
     */
    public function getAnimal()
    {
        return $this->animal;
    }
}

