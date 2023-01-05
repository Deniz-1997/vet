<?php

namespace App\Entity\Pet;

use App\Entity\Leaving\LeavingWeight;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Entity\Appointment\AppointmentWeight;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pet\WeightRepository")
 * @ORM\Table("pets_weights", schema="pet")
 */
class Weight
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
     * @var integer Вес, кг
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     * @SWG\Property(description="Вес, кг")
     */
    private $value;

    /**
     * @var Pet Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", inversedBy="weightHistory")
     */
    private $pet;

    /**
     * @var AppointmentWeight appointmentWeight
     * @ORM\OneToOne(targetEntity="App\Entity\Appointment\AppointmentWeight", mappedBy="weight")
     */
    private $appointmentWeight;

    /**
     * @var LeavingWeight leavingWeight
     * @ORM\OneToOne(targetEntity="App\Entity\Leaving\LeavingWeight", mappedBy="weight")
     */
    private $leavingWeight;

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Weight
     */
    public function setDate(\DateTime $date): Weight
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return Weight
     */
    public function setValue(int $value): Weight
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
     * @return AppointmentWeight|null
     */
    public function getAppointmentWeight()
    {
        return $this->appointmentWeight;
    }

    /**
     * @param AppointmentWeight $appointmentWeight
     * @return Weight
     */
    public function setAppointmentWeight(AppointmentWeight $appointmentWeight): self
    {
        $this->appointmentWeight = $appointmentWeight;
        return $this;
    }

    /**
     * @return LeavingWeight
     */
    public function getLeavingWeight()
    {
        return $this->leavingWeight;
    }

    /**
     * @param LeavingWeight $leavingWeight
     * @return Weight
     */
    public function setLeavingWeight(LeavingWeight $leavingWeight): self
    {
        $this->leavingWeight = $leavingWeight;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value ? $this->value / 1000 . 'кг' : null;
    }
}
