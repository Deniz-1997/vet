<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;

trait OrmParentIdTrait
{
    /**
     * @var int Идентификатор родителя (для иерархических справочников)
     *
     * @ORM\Column(type="integer", nullable=true)
     * @SWG\Property(description="Идентификатор родительского элемента", type="integer")
     */
    private int $parentId = 0;
    
    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId ?? 0;
    }
    
    /**
     * @param int|null $parentId
     *
     * @return self
     */
    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId ?? 0;
        
        return $this;
    }
}
