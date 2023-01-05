<?php

namespace App\Entity\Reference\Animal;

use App\Traits\ORMTraits\OrmDeletedTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Reference\Location\Location;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Entity\Reference\Vaccine\Vaccination;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Entity\Reference\Animal\Kind;
use App\Entity\Reference\Animal\Breed;
use App\Entity\Reference\Animal\Colour;
use App\Packages\DBAL\Types\AnimalGenderEnum;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Animal\AnimalRepository")
 * @ORM\Table(name="animal", schema="animal")
 */
class Animal
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmNameTrait, OrmDeletedTrait;

    /**
     * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Animal\Kind")
     * @ORM\JoinColumn(nullable=false)
     * @var Kind
     */
    private Kind $kind;

     /**
      * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Animal\Breed")
     * @ORM\JoinColumn(nullable=false)
     * @var Breed
     */
    private Breed $breed;

     /**
      * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Animal\Colour")
     * @ORM\JoinColumn(nullable=false)
     * @var Colour
     */
    private Colour $colour;

    /**
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\Location\Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=false)
     * @Assert\Valid()
     * @var Location
     */
    private Location $location;

    /**
     * @ORM\Column(name="birthdate", type="date", nullable=false)
     * @Groups ({"default"})
     * @Assert\NotBlank()
     * @Assert\Date()
     * @var DateTime
     */
    private DateTime $birthdate;

    /**
     * @Groups ({"default"})
     * @ORM\Column(type="string", name="owner", length=255, nullable=true)
     * @Assert\Length(max="255", maxMessage="animal.owner.max_length", groups={"Default", "vaccination_excel_import"})
     */
    private $owner;

    /**
     * @Groups ({"default"})
     * @ORM\Column(name="chip", type="string", length=15, nullable=true)
     * @Assert\Regex(pattern="/^\d{15}$/", message="animal.chip.value", groups={"Default", "vaccination_excel_import"})
     */
    private $chip;

    /**
     * @Groups ({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\Reference\Animal\AnimalStamp", cascade={"persist"}, mappedBy="animal")
     * @ORM\JoinColumn(nullable=false)
     * @var ArrayCollection
     */
    private $animalStamps;

    /**
     * @Groups ({"default"})
     * @ORM\ManyToMany(
     *      targetEntity="App\Entity\Reference\Vaccine\Vaccination",
     *      mappedBy="animals",
     *      cascade={"persist"}
     * )
     */
    private $vaccinations;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\AnimalGenderEnum", nullable=false, options={"default": AnimalGenderEnum::MALE})
     * @var AnimalGenderEnum
     */
    private AnimalGenderEnum $gender;

    public function __construct()
    {
        $this->vaccinations = new ArrayCollection;
        $this->name = $this->owner = '';
        $this->animalStamps = new ArrayCollection;
    }
   
    /**
     * @return Breed
     */
    public function getBreed(): ?Breed
    {
        return $this->breed ?? null;
    }
    /**
     * @param Breed $breed
     */
    public function setBreed(Breed $breed): self
    {
        $this->breed = $breed;
        $this->updateUniqueHash();
        return $this;
    }

    /**
     * @return Colour
     */
    public function getColour(): Colour
    {
        return $this->colour;
    }

    /**
     * @param Colour $colour
     */
    public function setColour(Colour $colour): self
    {
        $this->colour = $colour;
        $this->updateUniqueHash();
        return $this;

    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location): self
    {
        $this->location = $location;
        $this->updateUniqueHash();
        return $this;
    }

    private function updateUniqueHash()
    {
        $livingPlacePart = null;
        $lastLivingPlace = null;
        $lastLivingPlaceArrivalDate = null;
        $animalStampParts = [];
        ksort($animalStampParts);
    }

    /**
     * Get the value of owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @return  self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the value of chip
     */
    public function getChip()
    {
        return $this->chip;
    }

    /**
     * Set the value of chip
     *
     * @return  self
     */
    public function setChip($chip)
    {
        $this->chip = $chip;

        return $this;
    }

    /**
     * Получить все метки животного
     *
     * @return ArrayCollection|AnimalStamp[]
     */
    public function getAnimalStamps()
    {
        return $this->animalStamps;
    }

    /**
     * Получить текущую метку животного
     *
     * @return AnimalStamp[]|ArrayCollection|[]
     */
    public function getCurrentAnimalStamps()
    {
        return $this
            ->animalStamps
            ->filter(function (AnimalStamp $animalStamp) {
                return $animalStamp->getIsCurrent();
            });
    }

    /**
     * Добавить новую метку животного
     *
     * @param AnimalStamp $animalStamp
     * @return $this
     */
    public function addAnimalStamp(AnimalStamp $animalStamp)
    {
        $animalStamp->setAnimal($this);
        $this->animalStamps[] = $animalStamp;
        $this->updateUniqueHash();
        return $this;
    }

    /**
     * Удалить метку животного
     *
     * @param AnimalStamp $animalStamp
     */
    public function removeAnimalStamp(AnimalStamp $animalStamp)
    {
        $this->animalStamps->removeElement($animalStamp);
        $this->updateUniqueHash();
    }

    public function addVaccination(Vaccination $vaccination)
    {
        if (!$this->vaccinations->contains($vaccination)) {
            $this->vaccinations->add($vaccination);
            $vaccination->addAnimal($this);
        }
    }

    public function getVaccinations()
    {
        return $this->vaccinations;
    }

    /**
     * Get the value of birthdate
     *
     * @return  DateTime
     */ 
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set the value of birthdate
     *
     * @param  DateTime  $birthdate
     *
     * @return  self
     */ 
    public function setBirthdate(DateTime $birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get the value of kind
     *
     * @return  Kind
     */ 
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set the value of kind
     *
     * @param  Kind  $kind
     *
     * @return  self
     */ 
    public function setKind(Kind $kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get the value of gender
     *
     * @return  AnimalGenderEnum
     */ 
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     *
     * @param  AnimalGenderEnum  $gender
     *
     * @return  self
     */ 
    public function setGender(AnimalGenderEnum $gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function removeVaccination(Vaccination $vaccination): self
    {
        if ($this->vaccinations->removeElement($vaccination)) {
            $vaccination->removeAnimal($this);
        }

        return $this;
    }
}
