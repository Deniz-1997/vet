<?php

namespace App\Entity\Document;

use App\Entity\Reference\Product;
use App\Interfaces\DocumentProductInterface;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Document\DocumentProductRepository")
 */
class DocumentProduct implements DocumentProductInterface
{
    use OrmIdTrait;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="float", description="Количество едениц номенклатуры")
     * @Groups({"default"})
     * @Assert\Positive(message="document_product.quantity.positive")
     */
    private  $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     */
    private $product;
    

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     * @return $this
     */
    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
