<?php

namespace App\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User\User;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Interfaces\DocumentProductInterface;

/**
 * @ORM\Table(schema="shop")
 * @ORM\Entity(repositoryClass="App\Repository\Shop\ShopProductItemRepository")
 */
class ShopProductItem implements DocumentProductInterface
{
    use OrmIdTrait;

     /**
     * @var Product Товар
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     */
    private $product;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @SWG\Property(description="Пользователь")
     */
    private $user;

     /**
     * @var Stock|null Склад
     * @Groups({"default"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Stock")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Stock::class),
     *     description="Склад"
     * )
     */
    private $stock;

    /**
     * @var float
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Розничная цена")
     */
    private $price;

    /**
     * @var float
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

     /**
     * @var float
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;

     /**
     * @var ShopOrder Продажа в магазине
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop\ShopOrder", inversedBy="productItems")
     * @ORM\JoinColumn(nullable=true)
     */
    private $shopOrder;

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
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

     /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock|null $stock
     * @return AppointmentProductItem
     */
    public function setStock(?Stock $stock): ShopProductItem
    {
        $this->stock = $stock;
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
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

     /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
     /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }


     /**
     * @return ShopOrder|null
     */
    public function getShopOrder(): ?ShopOrder
    {
        return $this->shopOrder;
    }

    /**
     * @param ShopOrder|null $appointment
     * @return ShopProductItem
     */
    public function setShopOrder(?ShopOrder $shopOrder): self
    {
        $this->shopOrder = $shopOrder;

        return $this;
    }

    public function getMeasure(): string
    {
        $measure = $this->product->getMeasurementUnits();
        return $measure ? $measure->getName() : '';
    }
}
