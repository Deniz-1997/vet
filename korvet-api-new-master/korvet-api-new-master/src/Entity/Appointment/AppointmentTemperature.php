<?php

namespace App\Entity\Appointment;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Entity\Pet\Temperature;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Appointment\Appointment;

/**
 * @ORM\Table(schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\Appointment\AppointmentTemperatureRepository")
 */
class AppointmentTemperature
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var Temperature temperature
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Pet\Temperature", inversedBy="appointmentTemperature", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="temperature_id", referencedColumnName="id")
     * @SWG\Property(description="Температура")
     */
    private $temperature;

    /**
     * @var Appointment appointment
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Appointment\Appointment", inversedBy="appointmentTemperature", cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false, name="appointment_id", referencedColumnName="id")
     * @SWG\Property(description="Прием")
     */
    private $appointment;

     /**
     * @param Temperature $temperature
     * @return AppointmentTemperature
     */
    public function setTemperature(Temperature $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }
    /**
     * @return Temperature
     */
    public function getTemperature(): Temperature
    {
        return $this->temperature;
    }

    /**
     * @param Appointment $appointment
     * @return AppointmentTemperature
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
