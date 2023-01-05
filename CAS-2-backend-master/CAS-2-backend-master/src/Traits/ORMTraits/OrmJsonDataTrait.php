<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

trait OrmJsonDataTrait
{
    /**
     * @var array|null
     * @ORM\Column(type="json_array", options={"jsonb": true}, nullable=true)
     * @Groups({"groups"})
     */
    private ?array $jsonData;

    /**
     * @return array|null
     */
    public function getJsonData(): ?array
    {
        return $this->jsonData;
    }

    /**
     * @param array $jsonData
     * @return $this
     */
    public function setJsonData(array $jsonData): self
    {
        $this->jsonData = $jsonData;

        return $this;
    }
}
