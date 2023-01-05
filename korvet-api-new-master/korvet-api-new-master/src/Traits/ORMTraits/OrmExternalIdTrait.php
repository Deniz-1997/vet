<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

trait OrmExternalIdTrait
{
    /**
     * @var string|null Идентификатор
     *
     * @Groups({
     *     "default",
     *     "registration",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     * @SWG\Property(description="Идентификатор", type="string")
     */
    private $externalId;

    /**
     * @return ?string
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param null|string $externalId
     * @return $this
     */
    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSetExternalId(): bool
    {
        return !($this->externalId === null || $this->externalId === '');
    }
}
