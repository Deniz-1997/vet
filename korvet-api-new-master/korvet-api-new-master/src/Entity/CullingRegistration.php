<?php

namespace App\Entity;

use App\Entity\Embeddable\Address;
use App\Entity\Reference\Contractor\ContactPerson;
use App\Entity\Reference\Shelter;
use App\Entity\Reference\SterilizationType;
use App\Entity\Reference\TagColor;
use App\Entity\Reference\TagForm;
use App\Entity\Reference\VaccinationType;
use App\Entity\User\User;
use App\Enum\WildAnimalReleaseTypeEnum;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\IgnoreDeleted;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Packages\Annotation\History;

/**
 * Class CullingRegistration
 * @Gedmo\Loggable(logEntryClass="App\Entity\HistoryEntity")
 * @History(alias="cullingRegistration", relationField={"WildAnimal"})
 * @ORM\Entity(repositoryClass="App\Repository\CullingRegistrationRepository")
 */
class CullingRegistration
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var DateTime|null Дата отлова
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true)
     * @SWG\Property(description="Дата отлова", type="string", format="date", example="31.12.2017")
     */
    private $date;

    /**
     * @var Address Адрес
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Embedded(class="App\Entity\Embeddable\Address", columnPrefix="address_")
     * @SWG\Property(ref=@Model(type="App\Entity\Embeddable\Address"), description="Адрес отлова")
     */
    private $address;

    /**
     * @var string|null Координаты отлова
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Координаты отлова")
     */
    private $coordinates;

    /**
     * @var Contractor Контрагент, только чтение
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @SWG\Property(description="Контрагент, только чтение")
     */
    private $contractor;

    /**
     * @var ContactPerson Сотрудник контрагента
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Contractor\ContactPerson", inversedBy="cullingRegistrationHistory")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Contractor\ContactPerson::class),
     *     description="Сотрудник контрагента"
     * )
     * @Assert\NotNull()
     */
    private $contactPerson;

    /**
     * @var DateTime|null Дата обращения
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true)
     * @SWG\Property(description="Дата обращения отлова", type="string", format="date", example="31.12.2017")
     */
    private $appealDate;

    /**
     * @var string|null Номер обращения
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Номер обращения")
     */
    private $appealNumber;

    /**
     * @var WildAnimal Дикое животное
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\WildAnimal", inversedBy="cullingRegistrationHistory")
     * @SWG\Property(description="Дикое животное")
     */
    private $wildAnimal;

    /**
     * @var \DateTime|null Дата проведения стерилизации
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true, options={"default": null})
     * @SWG\Property(description="Дата проведения стерилизации", type="string", format="date", example="31.12.2017")
     */
    private $sterilizationDate;

    /**
     * @var SterilizationType|null Вид стерилизации
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\SterilizationType")
     * @ORM\JoinColumn(nullable=true),
     * @SWG\Property(ref=@Model(type=App\Entity\Reference\SterilizationType::class), description="Вид стерилизации")
     */
    private $sterilizationType;

    /**
     * @var User|null Сотрудник проводивший стерилизацию
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(ref=@Model(type=App\Entity\User\User::class), description="Сотрудник проведший стерилизацию")
     */
    private $sterilizationUser;

    /**
     * @var \DateTime|null Дата проведения вакцинации
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true, options={"default": null})
     * @SWG\Property(description="Дата проведения вакцинации", type="string", format="date", example="31.12.2017")
     */
    private $vaccinationDate;

    /**
     * @var VaccinationType|null Вакцина
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\VaccinationType")
     * @ORM\JoinColumn(nullable=true),
     * @SWG\Property(ref=@Model(type=App\Entity\Reference\VaccinationType::class), description="Вакцина")
     */
    private $vaccinationType;

    /**
     * @var User|null Сотрудник проведший вакцинацию
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(ref=@Model(type=App\Entity\User\User::class), description="Сотрудник проведший вакцинацию")
     */
    private $vaccinationUser;

    /**
     * @var \DateTime|null Дата биркования
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true, options={"default": null})
     * @SWG\Property(description="Дата биркования", type="string", format="date", example="31.12.2017")
     */
    private $tagDate;

    /**
     * @var TagForm|null Форма бирки
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\TagForm", fetch="EAGER")
     * @SWG\Property(description="Форма бирки", type="TagForm")
     */
    private $tagForm;

    /**
     * @var TagColor|null Цвет бирки
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\TagColor", fetch="EAGER")
     * @SWG\Property(description="Цвет бирки", type="TagColor")
     */
    private $tagColor;

    /**
     * @var string|null Номер бирки
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Номер бирки", type="string")
     */
    private $tagNumber;

    /**
     * @var string|null Текст на бирке
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Текст на бирке", type="string")
     */
    private $tagText;

    /**
     * @var WildAnimalReleaseTypeEnum Тип выбытия животного
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type=App\Enum\WildAnimalReleaseTypeEnum::class, nullable=true)
     * @SWG\Property(ref=@Model(type=App\Enum\WildAnimalReleaseTypeEnum::class), description="Тип выбытия животного")
     * @Assert\NotBlank(message="culling.release_type.not_blank")
     */
    private $releaseType;

    /**
     * @var \DateTime|null Дата выбытия
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true, options={"default": null})
     * @SWG\Property(description="Дата выбытия", type="string", format="date", example="31.12.2017")
     */
    private $releaseDate;

    /**
     * @var Shelter|null Приют
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Shelter")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(ref=@Model(type=App\Entity\Reference\Shelter::class), description="Приют")
     */
    private $releaseShelter;

    /**
     * @var Address|null Адрес
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Embedded(class="App\Entity\Embeddable\Address", columnPrefix="release_address_")
     * @SWG\Property(ref=@Model(type="App\Entity\Embeddable\Address"), description="Адрес выбытия")
     */
    private $releaseAddress;

    /**
     * @var string|null Новые хозяева
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Новые хозяева", type="string")
     */
    private $releaseNewOwners;

    /**
     * @var  string|null ИНН приюта
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="ИНН приюта", type="string")
     */
    private $releaseInn;

    /**
     * @var CullingRegistrationFile[] Загруженные файлы
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\CullingRegistrationFile",
     *     mappedBy="cullingRegistration",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     *    )
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @IgnoreDeleted()
     * @SWG\Property(description="Загруженные файлы")
     */
    private $cullingRegistrationFiles;

    /**
     * @var DateTime|null Дата начала карантинирования
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true)
     */
    private $quarantinePeriodStartTime;

    /**
     * @var DateTime|null Дата окончания карантинирования
     * @Groups({"api.cullingRegistration", "api.wildAnimal"})
     * @ORM\Column(type="date", nullable=true)
     */
    private $quarantinePeriodEndTime;

    /**
     * CullingRegistration constructor.
     */
    public function __construct()
    {
        $this->cullingRegistrationFiles = new ArrayCollection();
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     * @return CullingRegistration
     */
    public function setDate(?DateTime $date): CullingRegistration
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return null|Address
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param null|Address $address
     * @return CullingRegistration
     */
    public function setAddress(?Address $address): CullingRegistration
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    /**
     * @param null|string $coordinates
     * @return CullingRegistration
     */
    public function setCoordinates(?string $coordinates): CullingRegistration
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * @return ContactPerson
     */
    public function getContactPerson(): ContactPerson
    {
        return $this->contactPerson;
    }

    /**
     * @param ContactPerson $contactPerson
     * @return CullingRegistration
     */
    public function setContactPerson(ContactPerson $contactPerson): CullingRegistration
    {
        $this->contactPerson = $contactPerson;
        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getAppealDate(): ?DateTime
    {
        return $this->appealDate;
    }

    /**
     * @param null|DateTime $appealDate
     * @return CullingRegistration
     */
    public function setAppealDate(?DateTime $appealDate): CullingRegistration
    {
        $this->appealDate = $appealDate;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAppealNumber(): ?string
    {
        return $this->appealNumber;
    }

    /**
     * @param null|string $appealNumber
     * @return CullingRegistration
     */
    public function setAppealNumber(?string $appealNumber): CullingRegistration
    {
        $this->appealNumber = $appealNumber;
        return $this;
    }

    /**
     * @return WildAnimal
     */
    public function getWildAnimal(): WildAnimal
    {
        return $this->wildAnimal;
    }

    /**
     * @param WildAnimal $wildAnimal
     * @return CullingRegistration
     */
    public function setWildAnimal(WildAnimal $wildAnimal): CullingRegistration
    {
        $this->wildAnimal = $wildAnimal;
        return $this;
    }

    /**
     * @return Contractor
     */
    public function getContractor(): Contractor
    {
        return $this->contactPerson->getContractor();
    }

    /**
     * @return DateTime|null
     */
    public function getSterilizationDate(): ?DateTime
    {
        return $this->sterilizationDate;
    }

    /**
     * @param DateTime|null $sterilizationDate
     * @return CullingRegistration
     */
    public function setSterilizationDate(?DateTime $sterilizationDate): CullingRegistration
    {
        $this->sterilizationDate = $sterilizationDate;
        return $this;
    }

    /**
     * @return SterilizationType|null
     */
    public function getSterilizationType(): ?SterilizationType
    {
        return $this->sterilizationType;
    }

    /**
     * @param SterilizationType|null $sterilizationType
     * @return CullingRegistration
     */
    public function setSterilizationType(?SterilizationType $sterilizationType): CullingRegistration
    {
        $this->sterilizationType = $sterilizationType;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getSterilizationUser(): ?User
    {
        return $this->sterilizationUser;
    }

    /**
     * @param User|null $sterilizationUser
     * @return CullingRegistration
     */
    public function setSterilizationUser(?User $sterilizationUser): CullingRegistration
    {
        $this->sterilizationUser = $sterilizationUser;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getVaccinationDate(): ?DateTime
    {
        return $this->vaccinationDate;
    }

    /**
     * @param DateTime|null $vaccinationDate
     * @return CullingRegistration
     */
    public function setVaccinationDate(?DateTime $vaccinationDate): CullingRegistration
    {
        $this->vaccinationDate = $vaccinationDate;
        return $this;
    }

    /**
     * @return VaccinationType|null
     */
    public function getVaccinationType(): ?VaccinationType
    {
        return $this->vaccinationType;
    }

    /**
     * @param VaccinationType|null $vaccinationType
     * @return CullingRegistration
     */
    public function setVaccinationType(?VaccinationType $vaccinationType): CullingRegistration
    {
        $this->vaccinationType = $vaccinationType;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getVaccinationUser(): ?User
    {
        return $this->vaccinationUser;
    }

    /**
     * @param User|null $vaccinationUser
     * @return CullingRegistration
     */
    public function setVaccinationUser(?User $vaccinationUser): CullingRegistration
    {
        $this->vaccinationUser = $vaccinationUser;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getTagDate(): ?DateTime
    {
        return $this->tagDate;
    }

    /**
     * @param DateTime|null $tagDate
     * @return CullingRegistration
     */
    public function setTagDate(?DateTime $tagDate): CullingRegistration
    {
        $this->tagDate = $tagDate;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTagNumber(): ?string
    {
        return $this->tagNumber;
    }

    /**
     * @param null|string $tagNumber
     * @return CullingRegistration
     */
    public function setTagNumber(?string $tagNumber): CullingRegistration
    {
        $this->tagNumber = $tagNumber;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTagText(): ?string
    {
        return $this->tagText;
    }

    /**
     * @param null|string $tagText
     * @return CullingRegistration
     */
    public function setTagText(?string $tagText): CullingRegistration
    {
        $this->tagText = $tagText;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getReleaseDate(): ?DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param DateTime|null $releaseDate
     * @return CullingRegistration
     */
    public function setReleaseDate(?DateTime $releaseDate): CullingRegistration
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    /**
     * @return TagForm|null
     */
    public function getTagForm(): ?TagForm
    {
        return $this->tagForm;
    }

    /**
     * @param TagForm|null $tagForm
     * @return CullingRegistration
     */
    public function setTagForm(?TagForm $tagForm): CullingRegistration
    {
        $this->tagForm = $tagForm;
        return $this;
    }

    /**
     * @return TagColor|null
     */
    public function getTagColor(): ?TagColor
    {
        return $this->tagColor;
    }

    /**
     * @param TagColor|null $tagColor
     * @return CullingRegistration
     */
    public function setTagColor(?TagColor $tagColor): CullingRegistration
    {
        $this->tagColor = $tagColor;
        return $this;
    }

    /**
     * @return WildAnimalReleaseTypeEnum
     */
    public function getReleaseType(): WildAnimalReleaseTypeEnum
    {
        return $this->releaseType;
    }

    /**
     * @param WildAnimalReleaseTypeEnum $releaseType
     * @return CullingRegistration
     */
    public function setReleaseType(WildAnimalReleaseTypeEnum $releaseType): CullingRegistration
    {
        $this->releaseType = $releaseType;
        return $this;
    }

    /**
     * @return Shelter|null
     */
    public function getReleaseShelter(): ?Shelter
    {
        return $this->releaseShelter;
    }

    /**
     * @param Shelter|null $releaseShelter
     * @return CullingRegistration
     */
    public function setReleaseShelter(?Shelter $releaseShelter): CullingRegistration
    {
        $this->releaseShelter = $releaseShelter;
        return $this;
    }

    /**
     * @return Address|null
     */
    public function getReleaseAddress(): ?Address
    {
        return $this->releaseAddress;
    }

    /**
     * @param Address|null $releaseAddress
     * @return CullingRegistration
     */
    public function setReleaseAddress(?Address $releaseAddress): CullingRegistration
    {
        $this->releaseAddress = $releaseAddress;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getReleaseNewOwners(): ?string
    {
        return $this->releaseNewOwners;
    }

    /**
     * @param null|string $releaseNewOwners
     * @return CullingRegistration
     */
    public function setReleaseNewOwners(?string $releaseNewOwners): CullingRegistration
    {
        $this->releaseNewOwners = $releaseNewOwners;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReleaseInn(): ?string
    {
        return $this->releaseInn;
    }

    /**
     * @param string|null $releaseInn
     * @return CullingRegistration
     */
    public function setReleaseInn(?string $releaseInn): CullingRegistration
    {
        $this->releaseInn = $releaseInn;
        return $this;
    }

    /**
     * @return Collection|CullingRegistrationFile[]
     */
    public function getCullingRegistrationFiles(): Collection
    {
        return $this->cullingRegistrationFiles;
    }

    /**
     * @param CullingRegistrationFile $cullingRegistrationFile
     * @return CullingRegistration
     */
    public function addCullingRegistrationFile(CullingRegistrationFile $cullingRegistrationFile): self
    {
        if (!$this->cullingRegistrationFiles->contains($cullingRegistrationFile)) {
            $this->cullingRegistrationFiles[] = $cullingRegistrationFile;
            $cullingRegistrationFile->setCullingRegistration($this);
        }

        return $this;
    }

    /**
     * @param CullingRegistrationFile $cullingRegistrationFile
     * @return CullingRegistration
     */
    public function removeCullingRegistrationFile(CullingRegistrationFile $cullingRegistrationFile): self
    {
        if ($this->cullingRegistrationFiles->contains($cullingRegistrationFile)) {
            $this->cullingRegistrationFiles->removeElement($cullingRegistrationFile);
            // set the owning side to null (unless already changed)
            if ($cullingRegistrationFile->getCullingRegistration() === $this) {
                $cullingRegistrationFile->setCullingRegistration(null);
            }
        }

        return $this;
    }
    /**
     * @return DateTime|null
     */
    public function getQuarantinePeriodStartTime(): ?DateTime
    {
        return $this->quarantinePeriodStartTime;
    }

    /**
     * @param DateTime|null $quarantinePeriodStartTime
     * @return CullingRegistration
     */
    public function setQuarantinePeriodStartTime(?DateTime $quarantinePeriodStartTime): CullingRegistration
    {
        $this->quarantinePeriodStartTime = $quarantinePeriodStartTime;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getQuarantinePeriodEndTime(): ?DateTime
    {
        return $this->quarantinePeriodEndTime;
    }

    /**
     * @param DateTime|null $quarantinePeriodEndTime
     * @return CullingRegistration
     */
    public function setQuarantinePeriodEndTime(?DateTime $quarantinePeriodEndTime): CullingRegistration
    {
        $this->quarantinePeriodEndTime = $quarantinePeriodEndTime;
        return $this;
    }
}
