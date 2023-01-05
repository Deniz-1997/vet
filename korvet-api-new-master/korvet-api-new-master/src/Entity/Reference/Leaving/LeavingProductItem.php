<?php

namespace App\Entity\Reference\Leaving;

use App\Entity\Leaving\Leaving;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Entity\User\User;
use App\Interfaces\DocumentProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Packages\Annotation\History;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Leaving\LeavingProductItemRepository")
 * @ORM\Table(name="leaving_product_item", schema="leaving")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable(logEntryClass="App\Entity\HistoryEntity")
 * @History(alias="leavingProductItem", relationField={"leaving"})
 */
class LeavingProductItem implements DocumentProductInterface
{
    use OrmIdTrait;

    /**
     * @var Leaving Выезд
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Leaving\Leaving", inversedBy="productItems")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned()
     */
    private $leaving;

    /**
     * @var User|null
     * @Groups({"expose"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned()
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    private $creator;

    /**
     * @var Product Оказанные услуги и лекарственные препараты
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var Stock|null Склад
     * @Groups({"default"})
     * @Gedmo\Versioned()
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
     * @Gedmo\Versioned()
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Розничная цена")
     */
    private $price;

    /**
     * @var float
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    /**
     * @var float
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Единица измерения")
     */
    private $measure;

    /**
     * Услуга к которой привязываются товары
     *
     * @var LeavingProductItem|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Leaving\LeavingProductItem", inversedBy="children")
     * @SWG\Property(ref=@Model(type=\App\Entity\Reference\Leaving\LeavingProductItem::class))
     */
    private $parent;

    /**
     * Товары привзанные к услугам.
     *
     * @var LeavingProductItem[]|object
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="\App\Entity\Reference\Leaving\LeavingProductItem", mappedBy="parent")
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=\App\Entity\Reference\Leaving\LeavingProductItem::class)))
     */
    private $children;

    /**
     *  Транзитное поле для привязки товаров к услугам.
     *
     * @var LeavingProductItem[]|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=\App\Entity\Reference\Leaving\LeavingProductItem::class)))
     */
    private $items;

    /**
     * Цена с наценкой
     *
     * @var float|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Цена с наценкой")
     */
    private $priceWithCharge;

    /**
     * LeavingProductItem constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return LeavingProductItem|null
     */
    public function getParent(): ?LeavingProductItem
    {
        return $this->parent;
    }

    /**
     * @param LeavingProductItem|null $parent
     * @return LeavingProductItem
     */
    public function setParent(?LeavingProductItem $parent): LeavingProductItem
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return LeavingProductItem[]|object
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return LeavingProductItem[]|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param LeavingProductItem[]|null $items
     * @return LeavingProductItem
     */
    public function setItems(?array $items): LeavingProductItem
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return Leaving|null
     */
    public function getLeaving(): ?Leaving
    {
        return $this->leaving;
    }

    /**
     * @param Leaving|null $leaving
     * @return LeavingProductItem
     */
    public function setLeaving(?Leaving $leaving): self
    {
        $this->leaving = $leaving;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return LeavingProductItem
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = round(floatval($amount), 2);
    }

    /**
     * @return string|null
     */
    public function getMeasure(): ?string
    {
        return $this->measure;
    }

    /**
     * @param string|null $measure
     * @return $this
     */
    public function setMeasure(?string $measure): self
    {
        $this->measure = $measure;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * @param User|null $creator
     * @return $this
     */
    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPriceWithCharge(): ?float
    {
        return $this->priceWithCharge;
    }

    /**
     * @param float|null $priceWithCharge
     * @return LeavingProductItem
     */
    public function setPriceWithCharge(?float $priceWithCharge): LeavingProductItem
    {
        $this->priceWithCharge = $priceWithCharge;
        return $this;
    }

    /**
     * @return Stock|null
     */
    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock|null $stock
     * @return LeavingProductItem
     */
    public function setStock(?Stock $stock): LeavingProductItem
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     *
     * Формирование цены с наценкой
     *
     * @param LifecycleEventArgs $event
     *
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function preSave(LifecycleEventArgs $event)
    {
        if (empty($this->priceWithCharge)) {
            $this->priceWithCharge = $this->price;
        }

        $this->amount = round($this->priceWithCharge * $this->quantity, 2);
    }
}
