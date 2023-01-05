<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

trait OrmCodeTrait
{
    /**
     * @var string|null Строковый идентификатор элемента
     *
     * @Groups({
     *     "default",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     * @SWG\Property(description="Текстовый идентификатор", type="string")
     */
    private ?string $code = null;

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSetCode(): bool
    {
        return !($this->code === null || $this->code === '');
    }
}
