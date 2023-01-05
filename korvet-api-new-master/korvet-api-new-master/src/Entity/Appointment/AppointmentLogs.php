<?php

namespace App\Entity\Appointment;

use App\Entity\Reference\Appointment\AppointmentStatus;
use App\Entity\User\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\Appointment\AppointmentLogsRepository")
 */
class AppointmentLogs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var DateTime Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime")
     * @SWG\Property(description="Дата")
     */
    private $date;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @SWG\Property(description="Специалист")
     */
    private $user;

    /**
     * @var AppointmentStatus
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Appointment\AppointmentStatus")
     */
    private $status;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment\Appointment")
     * @SWG\Property(description="Прием")
     */
    private $appointment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return AppointmentStatus
     */
    public function getStatus(): AppointmentStatus
    {
        return $this->status;
    }

    /**
     * @param AppointmentStatus $status
     */
    public function setStatus(AppointmentStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return \App\Entity\Appointment\Appointment
     */
    public function getAppointment(): Appointment
    {
        return $this->appointment;
    }

    /**
     * @param Appointment $appointment
     */
    public function setAppointment(Appointment $appointment): void
    {
        $this->appointment = $appointment;
    }
}
