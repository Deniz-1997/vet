<?php

namespace App\Entity\Reference\Animal;

use App\Traits\ORMTraits\OrmDeletedTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Reference\Countries;
use App\Entity\Reference\Location\Location;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Animal\AnimalLivingPlaceRepository")
 * @ORM\Table(schema="animal", name="animal_living_place")
 */
class AnimalLivingPlace
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait;

    /**
     * @ORM\Column(name="arrival_date", type="datetime")
     *  @Groups ({"default"})
     * @Assert\NotNull
     * @Assert\LessThanOrEqual("today", message="animal.arrival_date.greater_than_today")
     * @var DateTime
     */
    private DateTime $arrivalDate;

    /**
     *  @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Countries")
     * @ORM\JoinColumn(nullable=true)
     * @var Countries|null
     */
    private ?Countries $country;

    /**
     *  @Groups ({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Reference\Location\Location", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @var Location|null
     */
    private ?Location $location;

    /**
     * @ORM\Column(name="address", type="string")
     * @Groups ({"default"})
     * @var string
     */
    private string $address;

    /**
     * @ORM\Column(name="is_current", type="boolean")
     *  @Groups ({"default"})
     * @var bool
     */
    private bool $isCurrent;

    /**
     * @ORM\Column(name="note", type="string")
     *  @Groups ({"default"})
     * @var string|null
     */
    private ?string $note;

    /**
     * @return DateTime
     */
    public function getArrivalDate(): DateTime
    {
        return $this->arrivalDate;
    }

    /**
     * @return Countries|null
     */
    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    /**
     * @param Countries|null $country
     */
    public function setCountry(Countries $country = null):void
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
    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
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
     * @return string|null
     */
    public function getNote(): ?string
    {
      return $this->note ?? null;
    }

    public function setArrivalDate(DateTime $arrivalDate): self
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getIsCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
