<?php

namespace App\EntityOld\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\KindRepository")
 * @ORM\Table(name="dictionary.kind")
 * @UniqueEntity(fields={"name"}, repositoryMethod="findInterferingKindsByName")
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessType("public_method")
 */
class Kind
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @Serializer\Type("string")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="name", length=255, nullable=false)
     * @Assert\NotBlank(message="kind.name.is_null", groups={"Default", "vaccination_excel_import"})
     * @Assert\Length(max="255", maxMessage="kind.name.max_length", groups={"Default", "vaccination_excel_import"})
     * @Serializer\Expose()
     */
    private $name;

    /** @ORM\Column(name="is_invalid", type="boolean", options={"default" = false}) */
    private $isInvalid = false;

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

    public function setIsInvalid($isInvalid)
    {
        $this->isInvalid = $isInvalid;
    }

    public function getIsInvalid()
    {
        return $this->isInvalid;
    }
}
