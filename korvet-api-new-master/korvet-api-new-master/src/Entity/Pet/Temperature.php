<?php

namespace App\Entity\Pet;

use App\Entity\Leaving\LeavingTemperature;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Entity\Appointment\AppointmentTemperature;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pet\TemperatureRepository")
 * @ORM\Table("pets_temperatures", schema="pet")
 */
class Temperature
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var \DateTime Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime")
     * @SWG\Property(description="Дата")
     */
    private $date;

    /**
     * @var float Температура
     * @Groups({"default"})
     * @ORM\Column(type="float")
     * @SWG\Property(description="Температура")
     */
    private $value;

    /**
     * @var Pet Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", inversedBy="temperatureHistory")
     */
    private $pet;

    /**
     * @var AppointmentTemperature appointmentTemperature
     * @ORM\OneToOne(targetEntity="App\Entity\Appointment\AppointmentTemperature", mappedBy="temperature")
     */
    private $appointmentTemperature;

    /**
     * @var LeavingTemperature leavingTemperature
     * @ORM\OneToOne(targetEntity="App\Entity\Leaving\LeavingTemperature", mappedBy="temperature")
     */
    private $leavingTemperature;

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Temperature
     */
    public function setDate(\DateTime $date): Temperature
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return Temperature
     */
    public function setValue(float $value): Temperature
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Pet
     */
    public function getPet()
    {
        return $this->pet;
    }

    /**
     * @param Pet $pet
     */
    public function setPet(Pet $pet)
    {
        $this->pet = $pet;
    }

    /**
     * @return AppointmentTemperature|null
     */
    public function getAppointmentTemperature()
    {
        return $this->appointmentTemperature;
    }

    /**
     * @param AppointmentTemperature $appointmentTemperature
     * @return Temperature
     */
    public function setAppointmentTemperature(AppointmentTemperature $appointmentTemperature): self
    {
        $this->appointmentTemperature = $appointmentTemperature;
        return $this;
    }

    /**
     * @return LeavingTemperature
     */
    public function getLeavingTemperature()
    {
        return $this->leavingTemperature;
    }

    /**
     * @param LeavingTemperature $leavingTemperature
     * @return $this
     */
    public function setLeavingTemperature(LeavingTemperature $leavingTemperature): self
    {
        $this->leavingTemperature = $leavingTemperature;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value ? $this->value : null;
    }
}
