<?php

namespace App\Entity\Pet;

use App\Entity\Embeddable\Address;
use App\Entity\Reference\AnimalDeath;
use App\Entity\Reference\Breed;
use App\Entity\Reference\PetType;
use App\Entity\Reference\ReasonRetiring;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Packages\Annotation\Enqueue\Consume;
use App\Packages\Annotation\Enqueue\CrudBatchConsume;
use App\Packages\Annotation\IgnoreDeleted;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Entity\Reference\Pet\PetLear;
use App\Packages\AMQP\Router\Route;
use App\Entity\Reference\VeterinaryPassportType;
use App\Entity\Reference\Pet\AggressiveType;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pet\PetRepository")
 * @ORM\Table("pets", schema="pet")
 *
 * @CrudBatchConsume({
 *     Route::TOPIC_CREATE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.create.batchCrud",
 *     ),
 *     Route::TOPIC_UPDATE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.update.batchCrud",
 *     ),
 *     Route::TOPIC_REPLACE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.replace.batchCrud",
 *     ),
 *     Route::TOPIC_DELETE_PREFIX: @Consume(
 *          queue="%kernel.environment%.korvet.entity.delete.batchCrud",
 *     )
 * })
 */
class Pet
{
    use OrmReferenceTrait, OrmExternalIdTrait;

    /**
     * @var PetType Тип животного
     * @Groups({"default"})
     * @SWG\Property(description="Тип животного, только чтение")
     */
    private $type;

    /**
     * @var Breed Порода
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Breed", fetch="EAGER")
     * @SWG\Property(description="Порода")
     * @Assert\NotBlank(message="pet.breed.not_blank")
     */
    private $breed;

    /**
     * @var string Кличка животного
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Кличка животного", type="string")
     * @Assert\NotBlank(message="pet.name.not_blank")
     */
    private string $name;

    /**
     * @var string Пол животного
     * @Groups({"api.pet"})
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Пол животного", type="string", enum={"MALE", "FEMALE"})
     * @Assert\NotBlank(message="pet.gender.not_blank")
     * @Assert\Choice(
     *     choices = { "MALE", "FEMALE" },
     *     message = "pet.gender.incorrect_value"
     * )
     */
    private $gender;

    /**
     * @var \DateTime Дата рождения
     * @Groups({"api.pet"})
     * @ORM\Column(type="date", nullable=true)
     * @SWG\Property(description="Дата рождения", type="string", format="date", example="31.12.2017")
     */
    private $birthday;

    /**
     * @var PetLear|null Масть
     * @Groups({"detail", "post", "put", "patch"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Pet\PetLear", cascade={"persist"})
     * @SWG\Property(type="object", ref=@Model(type=PetLear::class), description="Масть")
     */
    private $lear;

    /**
     * @var string|null Приметы
     * @Groups({"detail", "post", "put", "patch"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Приметы", type="string")
     */
    private $description;

    /**
     * @var string Номер чипа
     * @Groups({"detail", "post", "put", "patch"})
     * @ORM\Column(type="string", nullable=true, options={"default": ""})
     * @SWG\Property(description="Номер чипа", type="string")
     */
    private $chipNumber;

    /**
     * @var Address Адрес
     * @Groups({"detail", "patch", "post", "put"})
     * @ORM\Embedded(class="App\Entity\Embeddable\Address", columnPrefix="address_")
     * @SWG\Property(ref=@Model(type="App\Entity\Embeddable\Address"), description="Адрес")
     */
    private $address;

    /**
     * @var bool Использовать адрес владельца
     * @Groups({"detail", "patch", "post", "put"})
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     * @SWG\Property(description="Использовать адрес владельца", type="boolean")
     */
    private $useOwnerAddress = false;

    /**
     * @IgnoreDeleted()
     * @Groups({
     *     "pet.default"
     * })
     * @ORM\OneToMany(targetEntity="App\Entity\Pet\PetToOwner", mappedBy="pet")
     * @SWG\Property(description="Владельцы")
     */
    private $owners;

    /**
     * @var Collection|Identifier[]
     * @Groups({"detail", "patch", "post", "put"})
     * @ORM\OneToMany(targetEntity="App\Entity\Pet\Identifier", mappedBy="pet", cascade={"all"}, orphanRemoval=true, fetch="EAGER")
     * @SWG\Property(description="Идентификаторы")
     */
    private $identifiers;

    /**
     * @var Temperature изменение температуры животного
     * @Groups({"expose"})
     * @ORM\OneToMany(targetEntity="App\Entity\Pet\Temperature", mappedBy="pet")
     * @SWG\Property(description="История измерения температуры")
     */
    private $temperatureHistory;

    /**
     * @var Weight измнения массы животного
     * @Groups({"expose"})
     * @ORM\OneToMany(targetEntity="App\Entity\Pet\Weight", mappedBy="pet")
     * @SWG\Property(description="История измерения веса")
     */
    private $weightHistory;

    /**
     * @var Weight Актуальная масса животного
     * @Groups({"detail", "post", "path", "put"})
     * @SWG\Property(description="Текущий вес")
     */
    private $actualWeight;

    /**
     * @var Weight Актуальная температура животного
     * @Groups({"detail", "post", "path", "put"})
     * @SWG\Property(description="Текущая температура")
     */
    private $actualTemperature;

    /**
     * @var bool Животное агрессивно
     * @Groups({"api.pet"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Животное агрессивно")
     */
    private $aggressive;

    /**
     * @var AggressiveType|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Pet\AggressiveType", fetch="EAGER")
     * @SWG\Property(description="Степень агрессивности животного")
     */
    private $aggressiveType;

    /**
     * @var bool Адрес совпадает с адресом владельца
     * @Groups({"api.pet"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Адрес совпадает с адресом владельца")
     */
    private $sameAddress = false;

    /**
     * @var bool Признак смерти животного
     * @Groups({"api.pet"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean", description="Признак смерти животного")
     */
    private $isDead = false;

    /**
     * @var \DateTime Дата смерти животного
     * @Groups({"api.pet"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата смерти животного", type="string", format="date", example="31.12.2017")
     */
    private $dateOfDeath;

    /**
     * @var \DateTime
     * @Groups({"api.pet"})
     * @SWG\Property(description="Дата установки признака смерти", type="string", example="31.12.2017")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $isDeadCreatedAt;

    /**
     * @var AnimalDeath Причина смерти
     * @Groups({"api.pet"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\AnimalDeath", fetch="EAGER")
     * @SWG\Property(description="Причина смерти")
     */
    private $animalDeath;

    /**
     * @var bool Стерилизация
     * @Groups({"api.pet"})
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     * @SWG\Property(description="Стерилизация", type="boolean")
     */
    private $isSterilized = false;

    /**
     * @var \DateTime|null Дата стерилизации
     * @Groups({"api.pet"})
     * @SWG\Property(description="Дата стерилизации")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sterilizationDate;
    /**
     * @var \DateTime|null
     * @Groups({"api.pet"})
     * @SWG\Property(type="string", description="Дата вакцинации")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $vaccinationDate;
    /**
     * @var VeterinaryPassportType|null
     * @Groups({"api.pet"})
     * @SWG\Property(type="object", ref=@Model(type=VeterinaryPassportType::class),  description="Тип ветеринарного паспорта")
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\VeterinaryPassportType", cascade={"persist"})
     */
    private $veterinaryPassportType;
    /**
     * @var string|null
     * @Groups({"api.pet"})
     * @SWG\Property(type="string",  description="Номер ветеринарного паспорта")
     * @ORM\Column(type="string", nullable=true)
     */
    private $veterinaryPassportNumber;
    /**
     * @var bool
     * @Groups({"api.pet"})
     * @SWG\Property(type="boolean",  description="Выбытие")
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private $isRetiring;
    /**
     * @var \DateTime|null
     * @Groups({"api.pet"})
     * @SWG\Property(type="datetime",  description="Дата выбытия")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateOfRetiring;
    /**
     * @var ReasonRetiring
     * @Groups({"api.pet"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\ReasonRetiring",fetch="EAGER")
     * @SWG\Property(description="Причина выбытия")
     */
    private $petRetiring;





    /**
     * Pet constructor.
     */
    public function __construct()
    {
        $this->address = new Address();
        $this->identifiers = new ArrayCollection();
        $this->temperatureHistory = new ArrayCollection();
        $this->weightHistory = new ArrayCollection();
        $this->owners = new ArrayCollection();
    }


    /**
     * @return PetType
     */
    public function getType(): PetType
    {
        return $this->breed->getType();
    }

    /**
     * @return Breed
     */
    public function getBreed(): Breed
    {
        return $this->breed;
    }

    /**
     * @param Breed $breed
     * @return Pet
     */
    public function setBreed(Breed $breed): Pet
    {
        $this->breed = $breed;
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
     * @return Pet
     */
    public function setName(string $name): Pet
    {
        $this->name = $name;
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
     * @return Pet
     */
    public function setGender(string $gender): Pet
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     * @return Pet
     */
    public function setBirthday(\DateTime $birthday): Pet
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Pet
     */
    public function setDescription(?string $description): Pet
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getChipNumber(): ?string
    {
        return $this->chipNumber;
    }

    /**
     * @param string $chipNumber
     * @return Pet
     */
    public function setChipNumber(string $chipNumber): Pet
    {
        $this->chipNumber = $chipNumber;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return Pet
     */
    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUseOwnerAddress(): bool
    {
        return $this->useOwnerAddress;
    }

    /**
     * @param bool $useOwnerAddress
     * @return Pet
     */
    public function setUseOwnerAddress(bool $useOwnerAddress): Pet
    {
        $this->useOwnerAddress = $useOwnerAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * @param mixed $owners
     * @return Pet
     */
    public function setOwners($owners)
    {
        $this->owners = $owners;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getIdentifiers(): Collection
    {
        return $this->identifiers;
    }

    /**
     * @param Collection $identifiers
     * @return Pet
     */
    public function setIdentifiers(Collection $identifiers): Pet
    {
        $this->identifiers = $identifiers;
        return $this;
    }

    /**
     * Add identifier
     *
     * @param Identifier $identifier
     * @return Pet
     */
    public function addIdentifier(Identifier $identifier): Pet
    {
        $this->identifiers->add($identifier);
        $identifier->setPet($this);

        return $this;
    }

    /**
     * Remove product
     *
     * @param Identifier $identifier
     */
    public function removeIdentifier(Identifier $identifier)
    {
        $this->identifiers->removeElement($identifier);
    }

    /**
     * @return Temperature
     */
    public function getActualTemperature()
    {
        return $this->temperatureHistory->matching(
            Criteria::create()->where(Criteria::expr()->eq('deleted', false))->orderBy(['date' => Criteria::DESC])
        )->first();
    }

    /**
     * @return Weight
     */
    public function getActualWeight()
    {
        return $this->weightHistory->matching(
            Criteria::create()->where(Criteria::expr()->eq('deleted', false))->orderBy(['date' => Criteria::DESC])
        )->first();
    }

    /**
     * @return bool
     */
    public function isAggressive(): bool
    {
        return $this->aggressive ?? false;
    }

    /**
     * @param bool $aggressive
     * @return Pet
     */
    public function setAggressive(bool $aggressive): Pet
    {
        $this->aggressive = $aggressive;

        return $this;
    }
    /**
     * @return AggressiveType
     */
    public function getAggressiveType()
    {
        return $this->aggressiveType;
    }

    /**
     * @param AggressiveType $type
     * @return Pet
     */
    public function setAggressiveType(?AggressiveType $type):self
    {
        $this->aggressiveType = $type;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        if (!$this->birthday instanceof \DateTime) {
            return null;
        }

        return $this->birthday->diff(new \DateTime())->y;
    }

    /**
     * @return bool
     */
    public function isSameAddress(): bool
    {
        return $this->sameAddress;
    }

    /**
     * @param bool $sameAddress
     * @return $this
     */
    public function setSameAddress(bool $sameAddress): self
    {
        $this->sameAddress = $sameAddress;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDead(): ?bool
    {
        return $this->isDead;
    }

    /**
     * @param bool $isDead
     * @return Pet
     */
    public function setIsDead(bool $isDead): self
    {
        $this->isDead = $isDead;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateOfDeath(): ?\DateTimeInterface
    {
        return $this->dateOfDeath;
    }

    /**
     * @param \DateTimeInterface|null $dateOfDeath
     * @return Pet
     */
    public function setDateOfDeath(?\DateTimeInterface $dateOfDeath): self
    {
        $this->dateOfDeath = $dateOfDeath;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getIsDeadCreatedAt(): ?\DateTimeInterface
    {
        return $this->isDeadCreatedAt;
    }

    /**
     * @param \DateTimeInterface|null $isDeadCreatedAt
     * @return Pet
     */
    public function setIsDeadCreatedAt(?\DateTimeInterface $isDeadCreatedAt): self
    {
        $this->isDeadCreatedAt = $isDeadCreatedAt;

        return $this;
    }

    /**
     * @return AnimalDeath|null
     */
    public function getAnimalDeath(): ?AnimalDeath
    {
        return $this->animalDeath;
    }

    /**
     * @param AnimalDeath|null $animalDeath
     * @return Pet
     */
    public function setAnimalDeath(?AnimalDeath $animalDeath): self
    {
        $this->animalDeath = $animalDeath;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSterilized(): bool
    {
        return $this->isSterilized;
    }

    /**
     * @param bool $isSterilized
     * @return Pet
     */
    public function setIsSterilized(bool $isSterilized): Pet
    {
        $this->isSterilized = $isSterilized;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSterilizationDate(): ?\DateTime
    {
        return $this->sterilizationDate;
    }

    /**
     * @param \DateTime|null $sterilizationDate
     * @return Pet
     */
    public function setSterilizationDate(?\DateTime $sterilizationDate): Pet
    {
        $this->sterilizationDate = $sterilizationDate;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getVaccinationDate():?\DateTime
    {
        return $this->vaccinationDate;
    }

    /**
     * @param \DateTime|null $vaccinationDate
     *
     * @return Pet
     */
    public function setVaccinationDate(?\DateTime $vaccinationDate):Pet
    {
        $this->vaccinationDate = $vaccinationDate;

        return $this;
    }

    /**
     * @return PetLear|null
     */
    public function getLear(): ?PetLear
    {
        return $this->lear;
    }

    /**
     * @param PetLear|null $lear
     * @return Pet
     */
    public function setLear(?PetLear $lear): Pet
    {
        $this->lear = $lear;
        return $this;
    }

    /**
     * @return VeterinaryPassportType|null
     */
    public function getVeterinaryPassportType():?VeterinaryPassportType
    {
        return $this->veterinaryPassportType;
    }

    /**
     * @param VeterinaryPassportType|null $veterinaryPassportType
     *
     * @return Pet
     */
    public function setVeterinaryPassportType(?VeterinaryPassportType $veterinaryPassportType):Pet
    {
        $this->veterinaryPassportType = $veterinaryPassportType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVeterinaryPassportNumber():?string
    {
        return $this->veterinaryPassportNumber;
    }

    /**
     * @param string|null $veterinaryPassportNumber
     *
     * @return Pet
     */
    public function setVeterinaryPassportNumber(?string $veterinaryPassportNumber):Pet
    {
        $this->veterinaryPassportNumber = $veterinaryPassportNumber;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsRetiring(): bool
    {
        return $this->isRetiring;
    }

    /**
     * @param bool $isRetiring
     * @return $this
     */
    public function setIsRetiring(bool $isRetiring):Pet
    {
        $this->isRetiring = $isRetiring;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateOfRetiring(): ?\DateTime
    {
        return $this->dateOfRetiring;
    }

    /**
     * @param \DateTime|null $dateOfRetiring
     * @return $this
     */
    public function setDateOfRetiring(?\DateTime $dateOfRetiring):Pet
    {
        $this->dateOfRetiring = $dateOfRetiring;
        return $this;
    }

    /**
     * @return ReasonRetiring|null
     */
    public function getPetRetiring(): ?ReasonRetiring
    {
        return $this->petRetiring;
    }

    /**
     * @param ReasonRetiring|null $petRetiring
     * @return $this
     */
    public function  setPetRetiring(?ReasonRetiring $petRetiring): self
    {
        $this->petRetiring = $petRetiring;
        return $this;
    }
}
