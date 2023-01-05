<?php

namespace App\Entity;

use App\Entity\Owner\ContactPerson;
use App\Entity\Embeddable\Address;
use App\Entity\Owner\Embeddable\Entrepreneur;
use App\Entity\Owner\Embeddable\Farm;
use App\Entity\Owner\Embeddable\FullName;
use App\Entity\Owner\Embeddable\IndividualPerson;
use App\Entity\Owner\Embeddable\LegalEntity;
use App\Entity\Owner\Embeddable\Passport;
use App\Entity\Reference\Owner\Activity;
use App\Entity\Reference\Owner\LegalForm;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Reference\Owner\Status;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Packages\Annotation\IgnoreDeleted;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Validator\Constraint\ConditionalAssert;
use \App\Entity\Owner\FarmMember;
use function Symfony\Component\String\u;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OwnerRepository")
 * @ORM\Table("owners")
 * @ORM\HasLifecycleCallbacks()
 */
class Owner
{
    use OrmIdTrait, OrmReferenceTrait, OrmExternalIdTrait;

    const INDIVIDUAL_PERSON = 'INDIVIDUAL_PERSON';
    const LEGAL_ENTITY = 'LEGAL_ENTITY';
    const ENTREPRENEUR = 'ENTREPRENEUR';
    const FARM = 'FARM';

    /**
     * @var string Тип
     * @Groups({"api.owner"})
     * @ORM\Column(type="string")
     * @SWG\Property(description="Тип: физическое лицо (INDIVIDUAL_PERSON), юридическое лицо (LEGAL_ENTITY), индивидуальный предпририниматель (ENTREPRENEUR), КФХ (FARM - Крестьянское фермерское хозяйство)", enum={"INDIVIDUAL_PERSON","ENTREPRENEUR","LEGAL_ENTITY", "FARM"})
     * @Assert\NotBlank(message="owner.type.not_blank")
     * @Assert\Choice(
     *     choices = { "INDIVIDUAL_PERSON", "ENTREPRENEUR","LEGAL_ENTITY", "FARM" },
     *     message = "owner.type.incorrect_value"
     * )
     */
    private string $type;

    /**
     * @var string Наименование
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Наименование", type="string")
     * @Assert\Expression(
     *     expression="this.getType() in ['INDIVIDUAL_PERSON', 'ENTREPRENEUR'] or this.getName() !== null",
     *     message="name.not_blank"
     * )
     */
    private string $name;

    /**
     * @var string Телефон
     * @Groups({"default"})
     * @ORM\Column(type="string")
     * @SWG\Property(description="Телефон")
     * @Assert\NotBlank(message="owner.phone.not_blank")
     */
    private string $phone;

    /**
     * @var string| null Email
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Email")
     * @Assert\Email(
     *     message = "owner.email.incorrect_value",
     * )
     */
    private ?string $email = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", name="additional_contacts", length=1000, nullable=true)
     * @SWG\Property(type="string", description="Дополнительные контактные данные")
     */
    private ?string $additionalContacts = null;

    /**
     * @var string|null ИНН
     * @Groups({"api.owner"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="ИНН")
     */
    private ?string $inn = null;

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
     * @var Passport|null Паспорт
     * @Groups({"api.owner"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Passport", columnPrefix="passport_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Passport::class), description="Паспорт")
     * @Assert\Valid()
     */
    private ?Passport $passport = null;

    /**
     * @var Address Адрес
     * @Groups({"api.owner"})
     * @ORM\Embedded(class="App\Entity\Embeddable\Address", columnPrefix="address_")
     * @SWG\Property(ref=@Model(type="App\Entity\Embeddable\Address"), description="Адрес")
     * @Assert\NotBlank(message="owner.address.not_blank")
     */
    private Address $address;

    /**
     * @var IndividualPerson|null Данные физического лица
     * @Groups({"api.owner"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\IndividualPerson", columnPrefix="individual_person_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\IndividualPerson::class), description="Данные физического лица")
     */
    private ?IndividualPerson $individualPerson = null;

    /**
     * @var LegalEntity|null Данные юридического лица
     * @Groups({"api.owner"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\LegalEntity", columnPrefix="legal_entity_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\LegalEntity::class), description="Данные юридического лица")
     */
    private ?LegalEntity $legalEntity = null;

    /**
     * @var LegalForm|null Правовая форма
     *
     * @ConditionalAssert(
     *     "this.getType() in ['LEGAL_ENTITY']",
     *     @Assert\NotBlank(message="owner.legal_entity.legal_form_not_blank"),
     *     @Assert\Valid()
     * )
     *
     * @Groups({"api.owner"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Owner\LegalForm", fetch="EAGER")
     * @SWG\Property(ref=@Model(type=App\Entity\Reference\Owner\LegalForm::class), description="Правовая форма")
     */
    private ?LegalForm $legalForm = null;

    /**
     * @var Entrepreneur|null Данные индивидуального предпринимателя
     * @Groups({"api.owner"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Entrepreneur", columnPrefix="entrepreneur_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Entrepreneur::class), description="Данные индивидуального предпринимателя")
     */
    private ?Entrepreneur $entrepreneur = null;

    /**
     * @var Farm|null Данные КФХ
     * @Groups({"api.owner"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Farm", columnPrefix="farm_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Farm::class), description="Данные КФХ")
     */
    private ?Farm $farm = null;

    /**
     * @var FarmMember[]
     * @Groups({"api.owner"})
     * @ORM\OneToMany(targetEntity="App\Entity\Owner\FarmMember", mappedBy="owner", cascade={"all"}, orphanRemoval=true)
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=App\Entity\Owner\FarmMember::class)))
     */
    private $farmMembers;

    /**
     * @var Activity[]
     * @Groups({"api.owner"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Owner\Activity", cascade={"all"}, orphanRemoval=true)
     */
    private $activities;

    /**
     * @var string|null Описание видов деятельности
     * @Groups({"api.owner"})
     * @ORM\Column(type="string", options={"default"=""}, nullable=true)
     * @SWG\Property(description="Описание видов деятельности")
     */
    private ?string $customActivities = '';

    /**
     * @var Status|null Статус
     * @Groups({"api.owner"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Owner\Status", fetch="EAGER")
     * @SWG\Property(description="Статус")
     */
    private ?Status $status = null;

    /**
     * @var ContactPerson[]
     * @Groups({"api.owner"})
     * @ORM\OneToMany(targetEntity="App\Entity\Owner\ContactPerson", mappedBy="owner", cascade={"all"}, orphanRemoval=true)
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=App\Entity\Owner\ContactPerson::class)))
     */
    private $contactPersons;

    /**
     * @IgnoreDeleted()
     * @Groups({
     *     "owner.default",
     * })
     * @ORM\OneToMany(targetEntity="App\Entity\Pet\PetToOwner", mappedBy="owner")
     */
    private $pets;

    /**
     * @var \DateTime|null
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(type="string", description="Дата окончания договора платного оказания услуг")
     */
    private ?\DateTime $contractDateTo = null;
    /**
     * @var string Пол владельца
     * @Groups({"api.pet"})
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     */
    private string $gender;

    /**
     * Owner constructor.
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->farmMembers = new ArrayCollection();
        $this->contactPersons = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type ?? "";
    }

    /**
     * @param string $type
     * @return Owner
     */
    public function setType(string $type): Owner
    {
        $this->type = $type;
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
     * @return \App\Entity\Owner\ContactPerson[]|object
     */
    public function getContactPersons()
    {
        return $this->contactPersons;
    }

    /**
     * @param \App\Entity\Owner\ContactPerson $contactPerson
     * @return $this
     */
    public function addContactPerson(\App\Entity\Owner\ContactPerson $contactPerson)
    {
        $contactPerson->setOwner($this);

        if (!$this->contactPersons->contains($contactPerson)) {
            $this->contactPersons->add($contactPerson);
        }

        return $this;
    }

    /**
     * @param \App\Entity\Owner\ContactPerson $contactPerson
     * @return $this
     */
    public function removeContactPerson(\App\Entity\Owner\ContactPerson $contactPerson)
    {
        if ($this->contactPersons->contains($contactPerson)) {
            $this->contactPersons->removeElement($contactPerson);
        }

        return $this;
    }

    /**
     * @param string $phone
     * @return Owner
     */
    public function setPhone(string $phone): Owner
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Owner
     */
    public function setEmail(?string $email): Owner
    {
        $this->email = $email;

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
     * @return Owner
     */
    public function setInn(?string $inn): Owner
    {
        $this->inn = $inn;
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
     * @return Owner
     */
    public function setFullName(?FullName $fullName): Owner
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return Passport|null
     */
    public function getPassport(): ?Passport
    {
        return $this->passport && !is_null($this->passport->getNumber()) && !is_null($this->passport->getDocumentType()->code)
            ? $this->passport
            : null;
    }

    /**
     * @param Passport|null $passport
     * @return Owner
     */
    public function setPassport(?Passport $passport): Owner
    {
        $this->passport = $passport;
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
     * @return Owner
     */
    public function setAddress(Address $address): Owner
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return IndividualPerson|null
     */
    public function getIndividualPerson(): ?IndividualPerson
    {
        return $this->individualPerson;
    }

    /**
     * @param IndividualPerson|null $individualPerson
     * @return Owner
     */
    public function setIndividualPerson(?IndividualPerson $individualPerson): Owner
    {
        $this->individualPerson = $individualPerson;
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
     * @return Owner
     */
    public function setLegalEntity(?LegalEntity $legalEntity): Owner
    {
        $this->legalEntity = $legalEntity;
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
     * @return Owner
     */
    public function setEntrepreneur(?Entrepreneur $entrepreneur): Owner
    {
        $this->entrepreneur = $entrepreneur;
        return $this;
    }

    /**
     * @return Farm|null
     */
    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    /**
     * @param Farm|null $farm
     * @return Owner
     */
    public function setFarm(?Farm $farm): Owner
    {
        $this->farm = $farm;
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
     * @return Owner
     */
    public function setActivities($activities)
    {
        $this->activities = $activities;
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
     * @return Owner
     */
    public function setCustomActivities(?string $customActivities): Owner
    {
        $this->customActivities = $customActivities;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPets()
    {
        return $this->pets;
    }

    /**
     * @param mixed $pets
     * @return Owner
     */
    public function setPets($pets)
    {
        $this->pets = $pets;
        return $this;
    }

    /**
     * @return Status|null
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return Owner
     */
    public function setStatus(Status $status): Owner
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \App\Entity\Owner\FarmMember[]
     */
    public function getFarmMembers()
    {
        return $this->farmMembers;
    }

    /**
     * @param \App\Entity\Owner\FarmMember $member
     * @return $this
     */
    public function addFarmMember(\App\Entity\Owner\FarmMember $member)
    {
        $member->setOwner($this);

        if (!$this->farmMembers->contains($member)) {
            $this->farmMembers->add($member);
        }

        return $this;
    }

    /**
     * @param \App\Entity\Owner\FarmMember $member
     * @return $this
     */
    public function removeFarmMember(\App\Entity\Owner\FarmMember $member)
    {
        if ($this->farmMembers->contains($member)) {
            $this->farmMembers->removeElement($member);
        }

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
     * @return $this
     */
    public function setLegalForm(?LegalForm $legalForm)
    {
        $this->legalForm = $legalForm;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdditionalContacts():?string
    {
        return $this->additionalContacts;
    }

    /**
     * @param string|null $additionalContacts
     *
     * @return Owner
     */
    public function setAdditionalContacts(?string $additionalContacts):Owner
    {
        $this->additionalContacts = $additionalContacts;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return Owner
     */
    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getContractDateTo(): ?\DateTime
    {
        return $this->contractDateTo;
    }

    /**
     * @param \DateTime|null $contractDateTo
     *
     * @return Owner
     */
    public function setContractDateTo(?\DateTime $contractDateTo):Owner
    {
        $this->contractDateTo = $contractDateTo;

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
        /** @var Owner $self */
        $self = $lifecycleEventArgs->getEntity();
        if (in_array($self->getType(), [self::INDIVIDUAL_PERSON, self::ENTREPRENEUR]) && $this->getFullName()) {
            if ($self->getType() === self::INDIVIDUAL_PERSON) {
                $self->setName(sprintf(
                    '%s %s %s',
                    $this->getFullName()->getLastName(),
                    $this->getFullName()->getName(),
                    $this->getFullName()->getMiddleName()
                ));
            } elseif ($self->getType() === self::ENTREPRENEUR) {
                $self->setName(sprintf(
                    'Индивидуальный предприниматель %s %s %s',
                    $this->getFullName()->getLastName(),
                    $this->getFullName()->getName(),
                    $this->getFullName()->getMiddleName()
                ));
            }

        }
    }
}
