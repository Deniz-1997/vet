<?php

namespace App\EntityOld\Contractors;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="ContactTypeRepository")
 * @ORM\Table(
 *     name="contact_types",
 *     schema="contractors",
 *     indexes={
 *          @ORM\Index(name="contact_types_unique_idx", columns={"id"})
 *      }
 * )
 * @UniqueEntity(
 *     "id",
 *     repositoryMethod="findInterferingContactTypesByCriteria",
 *     message="contact_type.name_not_unique"
 * )
 */
class ContactType
{
    /**
     * @ORM\Column(name="id", type="string", length=16)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return ContactType
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return ContactType
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
}
