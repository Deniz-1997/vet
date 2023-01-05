<?php

namespace App\EntityOld\Contractors;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ContactRepository")
 * @ORM\Table(
 *     name="contacts",
 *     schema="contractors",
 * )
 */
class Contact
{

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

//    /**
//     * @ORM\ManyToOne(targetEntity="MartInfo\VeterinaryBundle\EntityOld\Contractors\ContactType")
//     * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=false)
//     * @Assert\NotBlank()
//     */
//    private $type;

    /**
     * @ORM\Column(name="value", type="string", length=128, nullable=true)
     * @Assert\Length(max="128")
     */
    private $value;


    /**
     * @ORM\Column(name="comment", type="string", length=512, nullable=true)
     * @Assert\Length(max="512")
     */
    private $comment;

//    /**
//     * @ORM\ManyToOne(
//     *     targetEntity="MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor",
//     *     inversedBy="contacts"
//     * )
//     * @ORM\JoinColumn(name="contractor_id", referencedColumnName="id", nullable=true)
//     */
//    private $contractor;


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
     * Set value
     *
     * @param string $value
     *
     * @return Contact
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Contact
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

//    /**
//     * Set type
//     *
//     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\ContactType $type
//     *
//     * @return Contact
//     */
//    public function setType(ContactType $type)
//    {
//        $this->type = $type;
//
//        return $this;
//    }

//    /**
//     * Get type
//     *
//     * @return \MartInfo\VeterinaryBundle\EntityOld\Contractors\ContactType
//     */
//    public function getType()
//    {
//        return $this->type;
//    }

    /**
     * Set contractor
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor $contractor
     *
     * @return Contact
     */
    public function setContractor(Contractor $contractor = null)
    {
        $this->contractor = $contractor;

        return $this;
    }

    /**
     * Get contractor
     *
     * @return \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor
     */
    public function getContractor()
    {
        return $this->contractor;
    }
}
