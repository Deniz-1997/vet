<?php

namespace App\Entity\Laboratory;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmActiveTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Laboratory\Packing;
use App\Entity\Laboratory\MaterialType;
use Symfony\Component\Serializer\Annotation\Groups; 
use Symfony\Component\Validator\Constraints as Assert;
use App\Interfaces\ProductInterface;
use App\Packages\DBAL\Types\VatRateEnum;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Validator\Constraints as AppAssert;
/**
 * @ORM\Table (schema="laboratory")
 * @ORM\Entity(repositoryClass="App\Repository\Laboratory\ProbeRepository")
 */
class Probe implements ProductInterface
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmActiveTrait;

    /**
     * @var Packing| null Упаковка
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\Packing")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Laboratory\Packing $packing = null;

    /**
     * @var MaterialType Тип материала
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\MaterialType")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="laboratory.material_type.not_blank")
     */
    private \App\Entity\Laboratory\MaterialType $materialType;

     /**
     * @var VatRateEnum| null Ставка НДС
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\VatRateEnum", nullable=true)
     * @SWG\Property(ref=@Model(
     *     type=App\Packages\DBAL\Types\VatRateEnum::class),
     *     description="Ставка НДС"
     * )
     */
    private ?VatRateEnum $vatRate = null;

     /**
      * @AppAssert\PositiveNumber
      * @var float
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Розничная цена")
     */
    private ?float $price = null;

    /**
     * @return Packing|null
     */
    public function getPacking(): ?Packing
    {
        return $this->packing;
    }

    /**
     * @param Packing|null $packing
     * @return Probe
     */
    public function setPacking(?Packing $packing): Probe
    {
        $this->packing = $packing;
        return $this;
    }

    /**
     * @return MaterialType|null
     */
    public function getMaterialType(): ?MaterialType
    {
        return $this->materialType;
    }

    /**
     * @param MaterialType|null $materialType
     * @return Probe
     */
    public function setMaterialType(?MaterialType $materialType): Probe
    {
        $this->materialType = $materialType;
        return $this;
    }

     /**
     * @return VatRateEnum
     */
    public function getVatRate(): VatRateEnum
    {
        return $this->vatRate;
    }

    /**
     * @param VatRateEnum $vatRate
     */
    public function setVatRate(VatRateEnum $vatRate): self
    {
        $this->vatRate = $vatRate;
        return $this;
    }

     /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }
}
