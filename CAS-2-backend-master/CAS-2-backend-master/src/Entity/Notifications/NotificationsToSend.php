<?php

namespace App\Entity\Notifications;

use App\Entity\Reference\Notifications\ReferenceNotificationsChannel;
use App\Repository\Notifications\NotificationsToSendRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=NotificationsToSendRepository::class)
 * @ORM\Table(schema="notifications")
 */
class NotificationsToSend
{
    const USER_TYPE = 'USER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private string $type;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    private int $value;

    /**
     * @var NotificationsList|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity=NotificationsList::class, inversedBy="toSend")
     */
    private NotificationsList $notificationsList;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Notifications\ReferenceNotificationsChannel", cascade={"persist"})
     * @SWG\Property(description="Канал оповещения оповещения")
     */
    private ReferenceNotificationsChannel $channel;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private ?\DateTimeInterface $created_at = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $sended_at = null;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     * @SWG\Property(description="Просмотренно-ли оповещение", type="boolean", default=false)
     */
    private ?bool $viewed = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     * @SWG\Property(description="Открыто-ли оповещение", type="boolean", default=false)
     */
    private ?bool $opened = false;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
     * @return $this
     */
    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return NotificationsList|null
     */
    public function getNotificationsList(): ?NotificationsList
    {
        return $this->notificationsList;
    }

    /**
     * @param NotificationsList|null $notificationsList
     * @return $this
     */
    public function setNotificationsList(?NotificationsList $notificationsList): self
    {
        if ($notificationsList) {
            $this->notificationsList = $notificationsList;
        }

        return $this;
    }

    /**
     * @return ReferenceNotificationsChannel
     */
    public function getChannel(): ReferenceNotificationsChannel
    {
        return $this->channel;
    }

    /**
     * @param ReferenceNotificationsChannel $channel
     * @return $this
     */
    public function setChannel(ReferenceNotificationsChannel $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSendedAt(): ?\DateTimeInterface
    {
        return $this->sended_at;
    }

    public function setSendedAt(?\DateTimeInterface $sended_at): self
    {
        $this->sended_at = $sended_at;

        return $this;
    }

     /**
     * @return bool
     */
    public function getViewed(): ?bool
    {
        return $this->viewed;
    }

    /**
     * @param bool $viewed
     * @return $this
     */
    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOpened(): ?bool
    {
        return $this->opened;
    }

    /**
     * @param bool $opened
     * @return $this
     */
    public function setOpened(bool $opened): self
    {
        $this->opened = $opened;

        return $this;
    }

}
