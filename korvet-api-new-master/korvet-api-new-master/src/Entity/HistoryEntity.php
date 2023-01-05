<?php

namespace App\Entity;

use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class HistoryEntity
 * @ORM\Entity(repositoryClass="App\Repository\HistoryEntityRepository")
 * @ORM\Table(name="history_entity",
 *     indexes={
 *          @ORM\Index(name="xaction", columns={"action"}),
 *          @ORM\Index(name="xloggedAt", columns={"logged_at"}),
 *          @ORM\Index(name="xobjectId", columns={"object_id"}),
 *          @ORM\Index(name="xobjectClass", columns={"object_class"}),
 *          @ORM\Index(name="xobjectMix", columns={"object_id","action","object_class"}),
 *      }
 * )
 */
class HistoryEntity extends AbstractLogEntry
{
    /**
     * @var integer $id
     * @Groups({"default"})
     * @SWG\Property(type="integer", example="1")
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $action
     * @Groups({"default"})
     * @SWG\Property(type="string", example="create")
     * @ORM\Column(type="string", length=8)
     */
    protected $action;

    /**
     * @var \DateTime $loggedAt
     * @Groups({"default"})
     * @SWG\Property(type="string", example="02.03.2019 14:49:23", description="Дата записи в лог")
     * @ORM\Column(name="logged_at", type="datetime")
     */
    protected $loggedAt;

    /**
     * @var string $objectId
     * @Groups({"default"})
     * @SWG\Property(type="string", example="1", description="Идентификатор логируемой сущности")
     * @ORM\Column(name="object_id", length=64, nullable=true)
     */
    protected $objectId;

    /**
     * @var string $objectClass
     * @Groups({"default"})
     * @SWG\Property(type="string", example="App\Entity\Task", description="Namespace класса логируемой сущности")
     * @ORM\Column(name="object_class", type="string", length=255)
     */
    protected $objectClass;

    /**
     * @var integer $version
     * @Groups({"default"})
     * @SWG\Property(type="string", example="2", description="Текущая версия изменений")
     * @ORM\Column(type="integer")
     */
    protected $version;

    /**
     * @var array $data
     * @Groups({"default"})
     * @SWG\Property(type="", description="Что изменено")
     * @ORM\Column(type="json", nullable=true)
     */
    protected $data;

    /**
     * @var array|null
     * @Groups({"default"})
     * @SWG\Property(type="", example="{""puzId"":{""old"":1,""new"":12}}", description="Все изменения {""old"":""oldValue"", ""new"":""newValue""}")
     * @ORM\Column(type="json", nullable=true)
     */
    protected ?array $diff = null;

    /**
     * @var User|null
     * @Groups({"default"})
     * @SWG\Property(type="", example="{""user"": { ""userId"": 1, ""username"": ""root"", ""clientId"": ""5_gkwg4co00csgw80kcwsckc08o4c8ogc888ocws8o65wzuxktyl""}")
     * @ORM\Embedded(class="App\Entity\User")
     */
    protected ?User $user = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @SWG\Property(type="string", example="Создано задание на сборку")
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $comment = null;

    /**
     * @todo по-другому не удаляется из бд
     * @deprecated
     * @var string $data
     */
    protected $username;

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return array|null
     */
    public function getDiff(): ?array
    {
        return $this->diff;
    }

    /**
     * @param array|null $diff
     */
    public function setDiff(?array $diff): void
    {
        $this->diff = $diff;
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
     *
     * @return HistoryEntity
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
