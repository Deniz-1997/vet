<?php

namespace App\Entity\Document;

use App\Entity\Reference\Product;
use App\Interfaces\DocumentProductInterface;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class InventoryDocumentProduct
 * @ORM\Entity(repositoryClass="App\Repository\Document\InventoryDocumentProductRepository")
 */
class InventoryDocumentProduct implements DocumentProductInterface
{
    use OrmIdTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     */
    private $product;

    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="float", description="Количество едениц номенклатуры по учету")
     * @Groups({"default"})
     * @Assert\PositiveOrZero(message="inventory_document_product.quantity_accounting.positive_or_zero")
     */
    private  $quantityAccounting;

    /**
     * @var float|null
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="float", description="Количество едениц номенклатуры фактическое")
     * @Groups({"default"})
     */
    private  $quantityActual;

    /**
     * @return float|null
     */
    public function getQuantityAccounting(): ?float
    {
        return $this->quantityAccounting;
    }

    /**
     * @param float|null $quantityAccounting
     * @return InventoryDocumentProduct
     */
    public function setQuantityAccounting(?float $quantityAccounting): InventoryDocumentProduct
    {
        $this->quantityAccounting = $quantityAccounting;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantityActual(): ?float
    {
        return $this->quantityActual;
    }

    /**
     * @param float|null $quantityActual
     * @return InventoryDocumentProduct
     */
    public function setQuantityActual(?float $quantityActual): InventoryDocumentProduct
    {
        $this->quantityActual = $quantityActual;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantityDifference(): float
    {
        return $this->quantityActual - $this->quantityAccounting;
    }

    /**
     * @return mixed
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }
}
