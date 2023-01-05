<?php

namespace App\Entity\Notifications;

use App\Entity\Reference\Notifications\ReferenceNotificationsChannel;
use App\Entity\Reference\Notifications\ReferenceNotificationsType;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=App\Repository\Notifications\NotificationsListRepository::class)
 * @ORM\Table(schema="notifications")
 */
class NotificationsList
{
    use OrmSortTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string Заголовок
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Заголовок", type="string")
     */
    private string $header;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Notifications\ReferenceNotificationsType")
     * @SWG\Property(description="Тип оповещения")
     */
    private ReferenceNotificationsType $type;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="json")
     */
    private array $data = [];

    /**
     * @var NotificationsToSend[]
     * @Groups({"default"})
     * @ORM\OneToMany(
     *     targetEntity=NotificationsToSend::class,
     *     mappedBy="notificationsList",
     *     cascade={"persist"},
     *     orphanRemoval=true
     *    )
     */
    private $toSend;

    /**
     * Owner constructor.
     */
    public function __construct()
    {
        $this->toSend = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ReferenceNotificationsType
     */
    public function getType(): ReferenceNotificationsType
    {
        return $this->type;
    }

    /**
     * @param ReferenceNotificationsType $type
     * @return $this
     */
    public function setType(ReferenceNotificationsType $type): self
    {
        $this->type = $type;

        return $this;
    }

   
    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Collection|NotificationsToSend[]
     */
    public function getToSend(): Collection
    {
        return $this->toSend;
    }

    /**
     * @param NotificationsToSend $toSend
     * @return $this
     */
    public function addToSend(NotificationsToSend $toSend): self
    {
        if (!$this->toSend->contains($toSend)) {
            $this->toSend[] = $toSend;
            $toSend->setNotificationsList($this);
        }

        return $this;
    }

    /**
     * @param NotificationsToSend $toSend
     * @return $this
     */
    public function removeToSend(NotificationsToSend $toSend): self
    {
        if ($this->toSend->removeElement($toSend)) {
            // set the owning side to null (unless already changed)
            if ($toSend->getNotificationsList() === $this) {
                $toSend->setNotificationsList(null);
            }
        }

        return $this;
    }
    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return NotificationsList
     */
    public function setHeader(string $header): self
    {
        $this->header = $header;
        return $this;
    }
}
