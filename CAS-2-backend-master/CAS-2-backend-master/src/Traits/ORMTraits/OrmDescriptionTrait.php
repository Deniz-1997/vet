<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;

trait OrmDescriptionTrait
{
    /**
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     *
     * @var string
     * @ORM\Column(type="text")
     * @SWG\Property(description="Описание", type="string")
     */
    private string $description;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    
}
