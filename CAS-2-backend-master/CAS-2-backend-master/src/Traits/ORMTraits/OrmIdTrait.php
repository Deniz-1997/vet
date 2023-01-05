<?php

namespace App\Traits\ORMTraits;

use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;

trait OrmIdTrait
{
    /**
     * @var int Идентификатор
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @SWG\Property(description="Идентификатор", type="integer")
     */
    private ?int $id = null;

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSetId(): bool
    {
        return $this->id !== null && $this->id !== 0;
    }
}
