<?php

namespace App\Entity\Appointment;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Entity\Pet\Weight;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Appointment\Appointment;

/**
 * @ORM\Table("appointment_weight", schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\Appointment\AppointmentWeightRepository")
 */
class AppointmentWeight
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var Weight weight
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Pet\Weight", inversedBy="appointmentWeight", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="weight_id", referencedColumnName="id")
     * @SWG\Property(description="Вес")
     */
    private $weight;

    /**
     * @var Appointment appointment
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Appointment\Appointment", inversedBy="appointmentWeight", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="appointment_id", referencedColumnName="id")
     * @SWG\Property(description="Прием")
     */
    private $appointment;

     /**
     * @param Weight $weight
     * @return AppointmentWeight
     */
    public function setWeight(Weight $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
    /**
     * @return Weight
     */
    public function getWeight(): Weight
    {
        return $this->weight;
    }

    /**
     * @param \App\Entity\Appointment\Appointment $appointment
     * @return AppointmentWeight
     */
    public function setAppointment(Appointment $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }
    /**
     * @return Appointment
     */
    public function getAppointment(): \App\Entity\Appointment\Appointment
    {
        return $this->appointment;
    }
}
