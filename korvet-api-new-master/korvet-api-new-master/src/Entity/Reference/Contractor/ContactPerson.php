<?php

namespace App\Entity\Reference\Contractor;

use App\Entity\Contractor;
use App\Entity\CullingRegistration;
use App\Entity\Owner\Embeddable\Person;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity()
 * @ORM\Table("contractor_contact_persons")
 */
class ContactPerson
{
    use OrmIdTrait , OrmSortTrait;

    /**
     * @var Contractor Контрагент
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Contractor", inversedBy="contactPersons")
     * @SWG\Property(ref=@Model(type=\App\Entity\Contractor::class), description="Контрагент")
     */
    private $contractor;

    /**
     * @var Person|null Данные сотрудника
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Person", columnPrefix="head_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Person::class), description="Данные сотрудника")
     */
    private $person;

    /**
     * @var string|null Должность
     * @Groups({"api.contractor"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Должность")
     */
    private $position;

    /**
     * @var string|null Комментарии
     * @Groups({"api.contractor"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Комментарии")
     */
    private $comment;

    /**
     * @var CullingRegistration[]|object История регистрации отлова
     * @Groups({"api.contractor"})
     * @ORM\OneToMany(targetEntity="\App\Entity\CullingRegistration", mappedBy="contactPerson")
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=\App\Entity\CullingRegistration::class)))
     */
    private $cullingRegistrationHistory;

    /**
     * @return Contractor
     */
    public function getContractor(): Contractor
    {
        return $this->contractor;
    }

    /**
     * @param Contractor $contractor
     * @return ContactPerson
     */
    public function setContractor(Contractor $contractor): ContactPerson
    {
        $this->contractor = $contractor;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return ContactPerson
     */
    public function setPerson(?Person $person): ContactPerson
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

    /**
     * @return CullingRegistration[]|object
     */
    public function getCullingRegistrationHistory()
    {
        return $this->cullingRegistrationHistory;
    }

    /**
     * @param CullingRegistration $cullingRegistration
     * @return $this
     */
    public function addCullingRegistration(CullingRegistration $cullingRegistration)
    {
        $cullingRegistration->setContactPerson($this);

        if (!$this->cullingRegistrationHistory->contains($cullingRegistration)) {
            $this->cullingRegistrationHistory->add($cullingRegistration);
        }

        return $this;
    }

    /**
     * @param CullingRegistration $cullingRegistration
     * @return $this
     */
    public function removeCullingRegistration(CullingRegistration $cullingRegistration)
    {
        if ($this->cullingRegistrationHistory->contains($cullingRegistration)) {
            $this->cullingRegistrationHistory->removeElement($cullingRegistration);
        }

        return $this;
    }
}
