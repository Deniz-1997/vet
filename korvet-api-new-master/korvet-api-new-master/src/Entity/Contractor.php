<?php

namespace App\Entity;

use App\Entity\Embeddable\Address;
use App\Entity\Owner\Embeddable\Entrepreneur;
use App\Entity\Owner\Embeddable\FullName;
use App\Entity\Reference\Contractor\ContactPerson;
use App\Entity\Owner\Embeddable\LegalEntity;
use App\Entity\Reference\Owner\Activity;
use App\Entity\Reference\Owner\LegalForm;
use App\Entity\Reference\Unit;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint\ConditionalAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Contractor
{
    use OrmReferenceTrait;

    const LEGAL_ENTITY = 'LEGAL_ENTITY';
    const ENTREPRENEUR = 'ENTREPRENEUR';

    /**
     * @var string Тип
     * @Groups({"api.contractor"})
     * @ORM\Column(type="string")
     * @SWG\Property(description="Тип: юридическое лицо (LEGAL_ENTITY)")
     * @Assert\NotBlank(message="contractor.type.not_blank")
     * @Assert\Choice(
     *     choices = {"LEGAL_ENTITY", "ENTREPRENEUR"},
     *     message = "contractor.type.incorrect_value"
     * )
     */
    private $type;

    /**
     * @var string Наименование
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Наименование", type="string")
     * @Assert\NotBlank(message="name.not_blank")
     */
    private string $name;

    /**
     * @var FullName|null ФИО
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\FullName", columnPrefix="full_name_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\FullName::class), description="ФИО")
     *
     * @ConditionalAssert(
     *     "this.getType() in ['INDIVIDUAL_PERSON', 'ENTREPRENEUR']",
     *     @Assert\NotBlank(message="owner.fullName.not_blank"),
     *     @Assert\Valid()
     * )
     */
    private $fullName;

    /**
     * @var LegalEntity|null Данные юридического лица
     * @Groups({"api.contractor"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\LegalEntity", columnPrefix="legal_entity_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\LegalEntity::class), description="Данные юридического лица")
     */
    private $legalEntity;

    /**
     * @var LegalForm|null Правовая форма
     *
     * @ConditionalAssert(
     *     "this.getType() in ['LEGAL_ENTITY']",
     *     @Assert\NotBlank(message="contractor.legal_entity.legal_form_not_blank"),
     *     @Assert\Valid()
     * )
     *
     * @Groups({"api.contractor"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Owner\LegalForm", fetch="EAGER")
     * @SWG\Property(ref=@Model(type=App\Entity\Reference\Owner\LegalForm::class), description="Правовая форма")
     */
    private $legalForm;

    /**
     * @var Entrepreneur|null Данные индивидуального предпринимателя
     * @Groups({"api.contractor"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Entrepreneur", columnPrefix="entrepreneur_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Entrepreneur::class), description="Данные индивидуального предпринимателя")
     */
    private $entrepreneur;

    /**
     * @var ContactPerson[] Сотрудники контрагентов
     * @Groups({"api.contractor"})
     * @ORM\OneToMany(targetEntity="\App\Entity\Reference\Contractor\ContactPerson", mappedBy="contractor", cascade={"all"}, orphanRemoval=true)
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=\App\Entity\Reference\Contractor\ContactPerson::class)), description="Сотрудники контрагентов")
     */
    private $contactPersons;

    /**
     * @var Unit[] Клиники
     * @Groups({"api.contractor"})
     * @ORM\OneToMany(targetEntity="App\Entity\Reference\Unit", mappedBy="contractor", cascade={"all"}, orphanRemoval=true)
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=App\Entity\Reference\Unit::class)), description="Клиники")
     */
    private $units;

    /**
     * @var string|null ИНН
     * @Groups({"api.contractor"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="ИНН")
     */
    private $inn;

    /**
     * @var string Телефон
     * @Groups({"api.contractor"})
     * @ORM\Column(type="string", options={"default": ""})
     * @SWG\Property(description="Телефон")
     * @Assert\NotBlank(message="contractor.phone.not_blank")
     */
    private $phone;

    /**
     * @var string|null Email
     * @Groups({"api.contractor"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Email")
     * @Assert\Email(
     *     message = "contractor.email.incorrect_value",
     * )
     */
    private $email;

    /**
     * @var Address Адрес
     * @Groups({"api.contractor"})
     * @ORM\Embedded(class="App\Entity\Embeddable\Address", columnPrefix="address_")
     * @SWG\Property(ref=@Model(type="App\Entity\Embeddable\Address"), description="Адрес")
     * @Assert\NotBlank(message="contractor.address.not_blank")
     */
    private $address;

    /**
     * @var Activity[]
     * @Groups({"api.contractor"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Owner\Activity", cascade={"all"}, orphanRemoval=true)
     */
    private $activities;

    /**
     * @var string|null Описание видов деятельности
     * @Groups({"api.contractor"})
     * @ORM\Column(type="string", options={"default"=""}, nullable=true)
     * @SWG\Property(description="Описание видов деятельности")
     */
    private $customActivities;

    /**
     * Contractor constructor.
     */
    public function __construct()
    {
        $this->contactPersons = new ArrayCollection();
        $this->units = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Contractor
     */
    public function setType(string $type): Contractor
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Contractor
     */
    public function setName(string $name): Contractor
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return LegalEntity|null
     */
    public function getLegalEntity(): ?LegalEntity
    {
        return $this->legalEntity;
    }

    /**
     * @param LegalEntity|null $legalEntity
     * @return Contractor
     */
    public function setLegalEntity(?LegalEntity $legalEntity): Contractor
    {
        $this->legalEntity = $legalEntity;
        return $this;
    }

    /**
     * @return LegalForm|null
     */
    public function getLegalForm(): ?LegalForm
    {
        return $this->legalForm;
    }

    /**
     * @param LegalForm|null $legalForm
     * @return Contractor
     */
    public function setLegalForm(?LegalForm $legalForm): Contractor
    {
        $this->legalForm = $legalForm;
        return $this;
    }

    /**
     * @return ContactPerson[]|array
     */
    public function getContactPersons()
    {
        return $this->contactPersons;
    }

    /**
     * @param ContactPerson $contactPerson
     * @return $this
     */
    public function addContactPerson(ContactPerson $contactPerson)
    {
        $contactPerson->setContractor($this);

        if (!$this->contactPersons->contains($contactPerson)) {
            $this->contactPersons->add($contactPerson);
        }

        return $this;
    }

    /**
     * @param ContactPerson $contactPerson
     * @return $this
     */
    public function removeContactPerson(ContactPerson $contactPerson)
    {
        if ($this->contactPersons->contains($contactPerson)) {
            $this->contactPersons->removeElement($contactPerson);
        }

        return $this;
    }

    /**
     * @return Unit[]|object
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param Unit $unit
     * @return $this
     */
    public function addUnit(Unit $unit)
    {
        $unit->setContractor($this);

        if (!$this->units->contains($unit)) {
            $this->units->add($unit);
        }

        return $this;
    }

    /**
     * @param Unit $unit
     * @return $this
     */
    public function removeUnit(Unit $unit)
    {
        if ($this->units->contains($unit)) {
            $this->units->removeElement($unit);
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getInn(): ?string
    {
        return $this->inn;
    }

    /**
     * @param null|string $inn
     * @return Contractor
     */
    public function setInn(?string $inn): Contractor
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Contractor
     */
    public function setPhone(string $phone): Contractor
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     * @return Contractor
     */
    public function setEmail(?string $email): Contractor
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return Contractor
     */
    public function setAddress(Address $address): Contractor
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCustomActivities(): ?string
    {
        return $this->customActivities;
    }

    /**
     * @param null|string $customActivities
     * @return Contractor
     */
    public function setCustomActivities(?string $customActivities): Contractor
    {
        $this->customActivities = $customActivities;
        return $this;
    }

    /**
     * @return FullName|null
     */
    public function getFullName(): ?FullName
    {
        return $this->fullName;
    }

    /**
     * @param FullName|null $fullName
     * @return Contractor
     */
    public function setFullName(?FullName $fullName): Contractor
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return Entrepreneur|null
     */
    public function getEntrepreneur(): ?Entrepreneur
    {
        return $this->entrepreneur;
    }

    /**
     * @param Entrepreneur|null $entrepreneur
     * @return Contractor
     */
    public function setEntrepreneur(?Entrepreneur $entrepreneur): Contractor
    {
        $this->entrepreneur = $entrepreneur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * @param mixed $activities
     * @return Contractor
     */
    public function setActivities($activities)
    {
        $this->activities = $activities;
        return $this;
    }

    /**
     * @param LifecycleEventArgs $lifecycleEventArgs
     *
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function preSave(LifecycleEventArgs $lifecycleEventArgs)
    {
        /** @var Contractor $self */
        $self = $lifecycleEventArgs->getEntity();
        if (in_array($self->getType(), [ self::ENTREPRENEUR]) && $this->getFullName()) {
            $self->setName(sprintf(
                'Индивидуальный предприниматель %s %s %s',
                $this->getFullName()->getLastName(),
                $this->getFullName()->getName(),
                $this->getFullName()->getMiddleName()
            ));
        }
    }
}
