<?php

namespace App\EntityOld\Animal;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\EntityOld\Vaccination\Vaccination;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\EntityOld\Dictionary\Breed;
use App\EntityOld\Dictionary\Colour;
use App\EntityOld\Dictionary\Kind;
use App\EntityOld\Location\Location;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalOld\AnimalRepository")
 * @ORM\Table(name="animal", schema="animal", indexes={
 *          @ORM\Index(name="animal_kind_id_idx", columns={"kind_id"}),
 *          @ORM\Index(name="animal_breed_id_idx", columns={"breed_id"}),
 *          @ORM\Index(name="animal_colour_id_idx", columns={"colour_id"}),
 *          @ORM\Index(name="animal_name_idx", columns={"name"}),
 *          @ORM\Index(name="animal_birthdate_idx", columns={"birthdate"}),
 *          @ORM\Index(name="animal_location_id_idx", columns={"location_id"})
 *      }
 * )
 */
class Animal
{
    use TimestampableEntity;

    /**
     * @var string $id
     *
     * @Groups({"default"})
     * @SWG\Property(type="guid", example="d3fec963-539e-4dfd-8abc-517673aaa7a8")
     *
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", name="name", length=255, nullable=false)
     * @SWG\Property(type="Тип")
     *
     * @Assert\NotBlank(message="animal.name.not_blank")
     * @Assert\Length(max="255", maxMessage="animal.name.max_length")
     */
    private string $name;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Dictionary\Kind", fetch="EAGER")
     * @ORM\JoinColumn(name="kind_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank(message="animal.kind.not_blank")
     * @Assert\NotNull(message="animal.kind.not_blank")
     * @Assert\Valid()
     *
     * @SWG\Property(type="Тип")
     *
     * @var Kind
     */
    private $kind;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Dictionary\Breed", fetch="EAGER")
     * @ORM\JoinColumn(name="breed_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank(message="animal.breed.not_blank")
     * @Assert\NotNull(message="animal.breed.not_blank")
     * @Assert\Valid()
     *
     * @SWG\Property(type="Порода")
     *
     * @var Breed;
     */
    private Breed $breed;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Dictionary\Colour", fetch="EAGER")
     * @ORM\JoinColumn(name="colour_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank(message="animal.colour.not_blank")
     * @Assert\NotNull(message="animal.colour.not_blank")
     * @Assert\Valid()
     *
     * @SWG\Property(type="Масть")
     *
     * @var Colour
     */
    private Colour $colour;

    /**
     * @ORM\OneToOne(targetEntity="App\EntityOld\Location\Location", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=false)
     * @Assert\Valid()
     * @var Location
     */
    private Location $location;

    /**
     * @ORM\Column(name="birthdate", type="date", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Date()
     * @var DateTime
     */
    private DateTime $birthdate;

     /**
     * @ORM\Column(type="string", name="owner", length=255, nullable=true)
     * @Assert\Length(max="255", maxMessage="animal.owner.max_length", groups={"Default", "vaccination_excel_import"})
     */
    private $owner;

   /**
     * @ORM\Column(name="chip", type="string", length=15, nullable=true)
     * @Assert\Regex(pattern="/^\d{15}$/", message="animal.chip.value", groups={"Default", "vaccination_excel_import"})
     */
    private $chip;

     /**
     * @ORM\OneToMany(targetEntity="AnimalStamp", mappedBy="animal", cascade={"persist"})
     * @Assert\Valid
     * @var ArrayCollection
     */
    private $animalStamps;

     /**
     * @ORM\ManyToMany(
     *      targetEntity="App\EntityOld\Vaccination\Vaccination",
     *      mappedBy="animals",
     *      cascade={"persist"}
     * )
     */
    private $vaccinations;

    public function __construct()
    {
        $this->vaccinations = new ArrayCollection;
        $this->name = $this->owner = '';
        $this->animalStamps = new ArrayCollection;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->updateUniqueHash();
    }

    /**
     * @return Breed
     */
    public function getBreed(): ?Breed
    {
        return isset($this->breed) ? $this->breed : null;
    }
    /**
     * @param Breed $breed
     */
    public function setBreed(Breed $breed): void
    {
        $this->breed = $breed;
        $this->updateUniqueHash();
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
    public function setColour(Colour $colour): void
    {
        $this->colour = $colour;
        $this->updateUniqueHash();
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
    public function setLocation(Location $location): void
    {
        $this->location = $location;
        $this->updateUniqueHash();
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

}
