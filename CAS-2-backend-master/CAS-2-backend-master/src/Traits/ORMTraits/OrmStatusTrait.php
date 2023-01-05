<?php

namespace App\Traits\ORMTraits;

use OpenApi\Annotations as SWG;

trait OrmStatusTrait
{
    /**
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     *
     * @var integer
     * @ORM\Column(type="integer")
     * @SWG\Property(description="Статус", type="integer")
     */
    private int $status;

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
