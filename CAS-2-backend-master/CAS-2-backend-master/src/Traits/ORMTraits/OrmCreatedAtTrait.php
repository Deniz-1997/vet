<?php

namespace App\Traits\ORMTraits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;

trait OrmCreatedAtTrait
{
    /**
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     *
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата создания", type="string")
     */
    private ?DateTime $createdAt=null;
    
    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        if (!$this->createdAt) {
            $this->createdAt = new DateTime();
        }
        
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     * @return self
     */
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt ?? new DateTime();
        
        return $this;
    }
}
