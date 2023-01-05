<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

trait OrmDeletedTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "permission.root"
     * })
     *
     * @SWG\Property(description="Удален", type="boolean", default=false)
     */
    private $deleted = false;

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return bool|null
     * @deprecated
     */
    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     * @return self
     */
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
