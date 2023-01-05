<?php

namespace App\Traits\ORMTraits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;

/**
 * Trait OrmUpdatedAtTrait
 * Чтобы автоматически при обновлении сущности устанавливалось значение в updatedAt нужно прописать у сущности
 * @ORM\HasLifecycleCallbacks(), тогда будет задействован метод onPreUpdate
 */
trait OrmUpdatedAtTrait
{
    /**
     * @var DateTime
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата последнего обновления", type="string")
     */
    private ?DateTime $updatedAt=null;

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $value
     *
     * @return self
     */
    public function setUpdatedAt(?DateTime $value): self
    {
        $this->updatedAt = $value ?? new DateTime();

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }
}
