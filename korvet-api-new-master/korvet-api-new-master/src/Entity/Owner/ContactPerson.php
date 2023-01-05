<?php

namespace App\Entity\Owner;


use App\Entity\Owner;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Owner\ContactPersonRepository")
 * @ORM\Table("owner_contact_persons")
 */
class ContactPerson
{
    use OrmIdTrait;

    /**
     * @var Owner Владелец
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner", inversedBy="contactPersons")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * @SWG\Property(description="Владелец")
     */
    private $owner;

    /**
     * @var Owner\Embeddable\Person|null Данные сотрудника
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Person", columnPrefix="head_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Person::class), description="Данные сотрудника")
     */
    private $person;

    /**
     * @var string|null Должность
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Должность")
     */
    private $position;

    /**
     * @var string|null Комментарии
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Комментарии")
     */
    private $comment;

    /**
     * @return Owner
     */
    public function getOwner(): Owner
    {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     * @return ContactPerson
     */
    public function setOwner(Owner $owner): ContactPerson
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return Embeddable\Person|null
     */
    public function getPerson(): ?Embeddable\Person
    {
        return $this->person;
    }

    /**
     * @param Embeddable\Person|null $person
     * @return ContactPerson
     */
    public function setPerson(?Embeddable\Person $person): ContactPerson
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param null|string $position
     * @return ContactPerson
     */
    public function setPosition(?string $position): ContactPerson
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return ContactPerson
     */
    public function setComment(?string $comment): ContactPerson
    {
        $this->comment = $comment;
        return $this;
    }


}
