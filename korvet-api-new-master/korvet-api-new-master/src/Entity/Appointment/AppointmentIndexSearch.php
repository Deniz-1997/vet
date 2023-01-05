<?php

namespace App\Entity\Appointment;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\IndexSearch\AppointmentIndexSearchRepository")
 */
class AppointmentIndexSearch
{
    use OrmIdTrait;

    /**
     * @ORM\Column(type="tsvector", nullable=true)
     */
    private $index;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Appointment\Appointment", cascade={"persist", "remove"})
     */
    private $appointment;

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param $index
     * @return $this
     */
    public function setIndex($index): self
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return Appointment|null
     */
    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    /**
     * @param Appointment|null $appointment
     * @return $this
     */
    public function setAppointment(?Appointment $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }
}
