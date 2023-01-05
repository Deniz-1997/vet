<?php

namespace App\Entity\Leaving;


use App\Entity\Reference\Leaving\LeavingStatus;
use App\Entity\User\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(schema="leaving")
 * @ORM\Entity(repositoryClass="App\Repository\Leaving\LeavingLogsRepository")
 */
class LeavingLogs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id ;

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
     * @var LeavingStatus
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Leaving\LeavingStatus")
     */
    private $leavingStatus;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Leaving\Leaving")
     * @SWG\Property(description="Прием")
     */
    private $leaving;

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
     * @return LeavingStatus
     */
    public function getLeavingStatus()
    {
        return $this->leavingStatus;
    }

    /**
     * @param LeavingStatus $leavingStatus
     */
    public function setLeavingStatus(LeavingStatus $leavingStatus): void
    {
        $this->leavingStatus = $leavingStatus;
    }

    /**
     * @return LeavingStatus
     */
    public function getLeaving()
    {
        return $this->leaving;
    }

    /**
     * @param Leaving $leaving
     */
    public function setLeaving(Leaving $leaving): void
    {
        $this->leaving = $leaving;
    }
}
