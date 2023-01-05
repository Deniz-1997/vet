<?php

namespace App\Traits\ORMTraits\Complex;

use App\Entity\User\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Exception\ApiException;

/**
* Trait ORMDocumentTrait
 * Чтобы автоматически при создании/обновлении сущности устанавливалось значение в createdAt/updatedAt
* и устанавливалось значение поля $id при создании сущности, нужно прописать у сущности
* @ORM\HasLifecycleCallbacks(), тогда будет задействован метод onPreUpdate/onPrePersist
*/
trait ORMDocumentTrait
{
    use OrmUpdatedAtTrait;

    /**
     * @var string|UuidInterface|null
     *
     * @Assert\Uuid(versions={4})
     * @SWG\Property(type="string", example="f5e4ff91-c98d-4fd3-a554-7c35592c5280")
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var DateTime|null
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(type="string", description="Редактируемая дата, на которую происходит регистрация документа в системе")
     */
    private ?DateTime $date = null;

    /**
     * @var integer
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @SWG\Property(type="integer", description="Номер, для удобного поиска документа по номеру в списке в фильтре")
     */
    private int $number;

    /**
     * @var DateTime|null
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(type="string", description="Дата создания документа")
     */
    private ?DateTime $createdAt;

    /**
     * @var User|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @SWG\Property(ref=@Model(type=App\Entity\User\User::class), description="Пользователь, создавший документ")
     */
    private ?User $creator = null;

    /**
     * @var User|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @SWG\Property(ref=@Model(type=App\Entity\User\User::class), description="Пользователь, обновивший документ")
     */
    private ?User $editor = null;

    /**
     * @var array<ApiException>|null
     * @Groups({"default"})
     * @ORM\Column(type="array")
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=ApiException::class)) , description="Массив ошибок при попытке проведения документа")
     */
    private ?array $errors = null;

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     * @return self
     */
    public function setDate(?DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return self
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return array<string>|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array<string>|null $errors
     * @return self
     */
    public function setErrors(?array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param string $error
     * @return self
     */
    public function addError(string $error): self
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return User|null
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * @param User|null $creator
     * @return self
     */
    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getEditor(): ?User
    {
        return $this->editor;
    }

    /**
     * @param User|null $editor
     * @return self
     */
    public function setEditor(?User $editor): self
    {
        $this->editor = $editor;
        return $this;
    }

    /**
     * @return string|UuidInterface|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string|UuidInterface|null $id
     * @return ORMDocumentTrait
     */
    public function setId($id = null): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSetId(): bool
    {
        return !empty($this->id);
    }

    /**
     * @ORM\PrePersist
     * @throws Exception
     */
    public function onPrePersist(): void
    {
        $this->createdAt = $this->createdAt ?? new DateTime();
        if (! $this->isSetId()) {
            $this->setId(Uuid::uuid4());
        }
    }
}
