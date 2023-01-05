<?php

namespace App\Entity;

use App\Entity\Reference\Event\EventStatus;
use App\Entity\Reference\EventType;
use App\Entity\User\User;
use App\Entity\Pet\Pet;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ORM\Table("events")
 */
class Event
{
    use OrmIdTrait, OrmDeletedTrait, OrmExternalIdTrait;

    /**
     * @var EventStatus Статус приема
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Event\EventStatus")
     */
    private $status;

    /**
     * @var \DateTime Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime")
     * @SWG\Property(description="Дата")
     */
    private $date;

    /**
     * @var Pet Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", fetch="EAGER")
     * @SWG\Property(description="Животное")
     */
    private $pet;

    /**
     * @var User Специалист
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", fetch="EAGER")
     * @SWG\Property(description="Специалист")
     */
    private $user;

    /**
     * @var EventType Тип мероприятия
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\EventType", fetch="EAGER")
     * @SWG\Property(description="Тип мероприятия")
     */
    private $type;

    /**
     * @var string Комментарий
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Комментарий")
     */
    private $comment;

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Event
     */
    public function setDate(\DateTime $date): Event
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return Pet
     */
    public function getPet(): Pet
    {
        return $this->pet;
    }

    /**
     * @param Pet $pet
     * @return Event
     */
    public function setPet(Pet $pet): Event
    {
        $this->pet = $pet;
        return $this;
    }

    /**
     * @return EventType
     */
    public function getType(): EventType
    {
        return $this->type;
    }

    /**
     * @param EventType $type
     * @return Event
     */
    public function setType(EventType $type): Event
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Event
     */
    public function setComment(string $comment): Event
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Event
     */
    public function setUser(User $user): Event
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return EventStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param EventStatus $status
     */
    public function setStatus (EventStatus $status)
    {
        $this->status = $status;
    }
}
