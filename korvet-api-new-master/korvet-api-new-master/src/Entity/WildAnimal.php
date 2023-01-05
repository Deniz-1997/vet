<?php

namespace App\Entity;

use App\Entity\Reference\Breed;
use App\Entity\Reference\PetType;
use App\Enum\WildAnimalAgeEnum;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\IgnoreDeleted;
use App\Traits\ORMTraits\OrmDeletedTrait;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WildAnimalRepository")
 * @ORM\Table("wild_animal")
 */
class WildAnimal
{
    use OrmIdTrait, OrmDeletedTrait, OrmExternalIdTrait;

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
     * @Assert\NotBlank(message="wild_animal.breed.not_blank")
     */
    private $breed;

    /**
     * @var string Пол животного
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Пол дикого животного", type="string", enum={"MALE", "FEMALE"})
     * @Assert\NotBlank(message="wild_animal.gender.not_blank")
     * @Assert\Choice(
     *     choices = { "MALE", "FEMALE" },
     *     message = "wild_animal.gender.incorrect_value"
     * )
     */
    private $gender;

    /**
     * @var string Описание животного
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, options={"default": ""})
     * @SWG\Property(description="Описание животного", type="string")
     */
    private $description;

    /**
     * @var string|null Номер чипа
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Номер чипа", type="string")
     */
    private $chipNumber;

    /**
     * @var DateTime|null Дата смерти животного
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата смерти животного", type="string", format="date", example="31.12.2017")
     */
    private $dateOfDeath;

    /**
     * @var string|null Причина смерти
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Причина смерти", type="string")
     */
    private $causeOfDeath;

    /**
     * @var bool|null Стерилизация
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(description="Стерилизация", type="boolean")
     */
    private $isSterilized;

    /**
     * @var CullingRegistration[] История регистрации отлова
     * @Groups({"default"})
      * @ORM\OneToMany(targetEntity="\App\Entity\CullingRegistration", mappedBy="wildAnimal", cascade={"all"}, orphanRemoval=true))
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=\App\Entity\CullingRegistration::class)))
     */
    private $cullingRegistrationHistory;

    /**
     * @var WildAnimalFile[] Загруженные файлы
     * @ORM\OneToMany(targetEntity="App\Entity\WildAnimalFile", mappedBy="wildAnimal", cascade={"persist"}, orphanRemoval=true)
     * @Groups({"default"})
     * @IgnoreDeleted()
     * @SWG\Property(description="Загруженные файлы")
     */
    private $wildAnimalFiles;

    /**
     * @var DateTime|null Дата последней вакцинации, только чтение
     * @Groups({"default"})
     * @SWG\Property(description="Дата последней вакцинации, только чтение")
     */
    private $lastVaccinationDate;

    /**
     * @var DateTime|null Дата стерилизации, только чтение
     * @Groups({"default"})
     * @SWG\Property(description="Дата стерилизации, только чтение")
     */
    private $sterilizationDate;

     /**
     * @var string|null Номер бирки, только чтение
     * @Groups({"default"})
     * @SWG\Property(description="Номер бирки, только чтение")
     */
    private $tagNumber;

    /**
     * @var DateTime|null Дата рождения
     * @Groups({"default"})
     * @ORM\Column(type="date", nullable=true)
     * @SWG\Property(description="Дата рождения", type="string", format="date", example="31.12.2017")
     */
    private $birthday;

    /**
     * @var string|null Номер животного
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Номер животного", type="string")
     */
    private $animalNumber;

    /**
     * @var bool Животное агрессивно
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Животное агрессивно")
     */
    private $aggressive;


    /**
     * WildAnimal constructor.
     */
    public function __construct()
    {
        $this->cullingRegistrationHistory = new ArrayCollection();
        $this->wildAnimalFiles = new ArrayCollection();
    }

    /**
     * @return DateTime|null
     */
    public function getLastVaccinationDate(): ?DateTime
    {
        $cullingRegistrationHistory = new ArrayCollection(array_reverse($this->cullingRegistrationHistory->toArray()));
        /** @var CullingRegistration $cullingRegistration */
        foreach($cullingRegistrationHistory->getIterator() as $cullingRegistration) {
            if ($cullingRegistration->getVaccinationDate()) {
                $date = $cullingRegistration->getVaccinationDate();
                break;
            }
        }
        return $date ?? null;
    }

    /**
     * @return DateTime|null
     */
    public function getSterilizationDate(): ?DateTime
    {
        $cullingRegistrationHistory = new ArrayCollection(array_reverse($this->cullingRegistrationHistory->toArray()));
        /** @var CullingRegistration $cullingRegistration */
        foreach($cullingRegistrationHistory->getIterator() as $cullingRegistration) {
            if ($cullingRegistration->getSterilizationDate()) {
                $date = $cullingRegistration->getSterilizationDate();
                break;
            }
        }
        return $date ?? null;
    }

     /**
     * @return string|null
     */
    public function getTagNumber(): ?string
    {
        $cullingRegistrationHistory = new ArrayCollection($this->cullingRegistrationHistory->toArray());
        /** @var CullingRegistration $cullingRegistration */
        foreach($cullingRegistrationHistory->getIterator() as $cullingRegistration) {
            if ($cullingRegistration->getTagNumber()) {
                $tagNumber = $cullingRegistration->getTagNumber();
                break;
            }
        }
        return $tagNumber ?? null;
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
     * @return WildAnimal
     */
    public function setBreed(Breed $breed): WildAnimal
    {
        $this->breed = $breed;
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
     * @return WildAnimal
     */
    public function setGender(string $gender): WildAnimal
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return WildAnimal
     */
    public function setDescription(string $description): WildAnimal
    {
        $this->description = $description;
        return $this;
    }
    /**
     * @return null|string
     */
    public function getChipNumber(): ?string
    {
        return $this->chipNumber;
    }

    /**
     * @param null|string $chipNumber
     * @return WildAnimal
     */
    public function setChipNumber(?string $chipNumber): WildAnimal
    {
        $this->chipNumber = $chipNumber;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateOfDeath(): ?DateTime
    {
        return $this->dateOfDeath;
    }

    /**
     * @param DateTime|null $dateOfDeath
     * @return WildAnimal
     */
    public function setDateOfDeath(?DateTime $dateOfDeath): WildAnimal
    {
        $this->dateOfDeath = $dateOfDeath;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCauseOfDeath(): ?string
    {
        return $this->causeOfDeath;
    }

    /**
     * @param null|string $causeOfDeath
     */
    public function setCauseOfDeath(?string $causeOfDeath): void
    {
        $this->causeOfDeath = $causeOfDeath;
    }

    /**
     * @return bool|null
     */
    public function getIsSterilized(): ?bool
    {
        return $this->isSterilized;
    }

    /**
     * @param bool|null $isSterilized
     * @return WildAnimal
     */
    public function setIsSterilized(?bool $isSterilized): WildAnimal
    {
        $this->isSterilized = $isSterilized;
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
        $cullingRegistration->setWildAnimal($this);

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

    /**
     * @return Collection|WildAnimalFile[]
     */
    public function getWildAnimalFiles(): Collection
    {
        return $this->wildAnimalFiles;
    }

    /**
     * @param WildAnimalFile $wildAnimalFile
     * @return WildAnimal
     */
    public function addWildAnimalFile(WildAnimalFile $wildAnimalFile): self
    {
        if (!$this->wildAnimalFiles->contains($wildAnimalFile)) {
            $this->wildAnimalFiles[] = $wildAnimalFile;
            $wildAnimalFile->setWildAnimal($this);
        }

        return $this;
    }

    /**
     * @param WildAnimalFile $wildAnimalFile
     * @return WildAnimal
     */
    public function removeWildAnimalFile(WildAnimalFile $wildAnimalFile): self
    {
        if ($this->wildAnimalFiles->contains($wildAnimalFile)) {
            $this->wildAnimalFiles->removeElement($wildAnimalFile);
            // set the owning side to null (unless already changed)
            if ($wildAnimalFile->getWildAnimal() === $this) {
                $wildAnimalFile->setWildAnimal(null);
            }
        }

        return $this;
    }

     /**
     * @return DateTime|null
     */
    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    /**
     * @param DateTime|null $birthday
     * @return WildAnimal
     */
    public function setBirthday(?DateTime $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }
    
    /**
     * @return null|string
     */
    public function getAnimalNumber(): ?string
    {
        return $this->animalNumber;
    }

    /**
     * @param null|string $animalNumber
     * @return WildAnimal
     */
    public function setAnimalNumber(?string $animalNumber): WildAnimal
    {
        $this->animalNumber = $animalNumber;
        return $this;
    }

     /**
     * @return bool
     */
    public function getAggressive(): bool
    {
        return $this->aggressive ?? false;
    }

    /**
     * @param bool $aggressive
     * @return WildAnimal
     */
    public function setAggressive(bool $aggressive): WildAnimal
    {
        $this->aggressive = $aggressive;
        return $this;
    }
}
