<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

trait OrmActiveTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": true})
     *
     * @Groups({
     *     "permission.root"
     * })
     *
     * @SWG\Property(description="Активность", type="boolean", default=true)
     */
    private bool $active = true;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
