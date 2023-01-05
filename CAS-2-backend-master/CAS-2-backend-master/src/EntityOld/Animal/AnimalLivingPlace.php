<?php

namespace App\EntityOld\Animal;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\EntityOld\Dictionary\Country;
use App\EntityOld\Location\Location;

/**
 * Class AnimalLivingPlace
 * @package App\EntityOld\Animal
 * @ORM\Entity(repositoryClass="App\Repository\AnimalOld\AnimalLivingPlaceRepository")
 * @ORM\Table(
 *     schema="animal",
 *     name="animal_living_places"
 * )
 */
class AnimalLivingPlace
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="guid", name="id")
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private string $id;

    /**
     * @ORM\Column(name="arrival_date", type="datetime")
     * @Assert\NotNull
     * @Assert\LessThanOrEqual("today", message="animal.arrival_date.greater_than_today")
     * @var DateTime
     */
    private DateTime $arrivalDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Dictionary\Country")
     * @ORM\JoinColumn(name="country_iso", referencedColumnName="iso", nullable=true)
     * @var Country|null
     */
    private ?Country $country;

    /**
     * @ORM\OneToOne(targetEntity="App\EntityOld\Location\Location", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=true)
     * @var Location|null
     */
    private ?Location $location;

    /**
     * @ORM\Column(name="address", type="string")
     * @var ?string
     */
    private ?string $address;

    /**
     * @ORM\Column(name="is_current", type="boolean")
     * @var bool
     */
    private bool $isCurrent;

    /**
     * @ORM\Column(name="note", type="string")
     * @var string|null
     */
    private ?string $note;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getArrivalDate(): DateTime
    {
        return $this->arrivalDate;
    }

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @param Country|null $country
     */
    public function setCountry(Country $country = null):void
    {
        $this->country = $country;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location|null $location
     */
    public function setLocation(Location $location = null):void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(string $address = null):void
    {
        $this->address = $address;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }

    /**
     * @param bool $isCurrent
     */
    public function setIsCurrent(bool $isCurrent):void
    {
        $this->isCurrent = $isCurrent;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note):void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
      return isset($this->note) ? $this->note : null;
    }
}
