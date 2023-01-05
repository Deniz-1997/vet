<?php

namespace App\Entity\Embeddable;

use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Packages\DBAL\Types\ProductCodeTypeEnum;
use function Symfony\Component\String\u;

/**
 * @ORM\Embeddable()
 */
class ProductCode
{
    /**
     * @var ProductCodeTypeEnum Тип маркировки
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\ProductCodeTypeEnum", nullable=true)
     * @SWG\Property(type="object", ref=@Model(type=ProductCodeTypeEnum::class), description="Тип маркировки")
     */
    private $type;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $gtin;

    /**
     * @var string|null Серийный номер
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Тип маркировки")
     */
    private $serial;

    /**
     * @return ProductCodeTypeEnum|null
     */
    public function getType():?ProductCodeTypeEnum
    {
        return $this->type;
    }

    /**
     * @param ProductCodeTypeEnum|null $type
     */
    public function setType(?ProductCodeTypeEnum $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getGtin(): ?string
    {
        return $this->gtin;
    }

    /**
     * @param string|null $gtin
     */
    public function setGtin(?string $gtin): void
    {
        $this->gtin = $gtin;
    }

    /**
     * @return string|null
     */
    public function getSerial(): ?string
    {
        return $this->serial;
    }

    /**
     * @param string|null $serial
     */
    public function setSerial(?string $serial): void
    {
        $this->serial = $serial;
    }
}
