<?php

namespace App\Entity\Reference\Appointment;

use App\Entity\Appointment\Appointment;
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
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Appointment\AppointmentProductItemRepository")
 * @ORM\Table(name="appointment_product_item", schema="appointment")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable(logEntryClass="App\Entity\HistoryEntity")
 * @History(alias="appointmentProductItem", relationField={"appointment"})
 */
class AppointmentProductItem implements DocumentProductInterface
{
    use OrmIdTrait;

    /**
     * @var Appointment Прием
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment\Appointment",
     *      inversedBy="productItems",
     *     )
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned()
     */
    private $appointment;

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
     * @var AppointmentProductItem|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Appointment\AppointmentProductItem", inversedBy="children")
     * @SWG\Property(ref=@Model(type=\App\Entity\Reference\Appointment\AppointmentProductItem::class))
     */
    private $parent;

    /**
     * Товары привзанные к услугам.
     *
     * @var AppointmentProductItem[]|object
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="\App\Entity\Reference\Appointment\AppointmentProductItem", mappedBy="parent")
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=\App\Entity\Reference\Appointment\AppointmentProductItem::class)))
     */
    private $children;

    /**
     *  Транзитное поле для привязки товаров к услугам.
     *
     * @var AppointmentProductItem[]|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=\App\Entity\Reference\Appointment\AppointmentProductItem::class)))
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
     * AppointmentProductItem constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return AppointmentProductItem|null
     */
    public function getParent(): ?AppointmentProductItem
    {
        return $this->parent;
    }

    /**
     * @param AppointmentProductItem|null $parent
     * @return AppointmentProductItem
     */
    public function setParent(?AppointmentProductItem $parent): AppointmentProductItem
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return AppointmentProductItem[]|object
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return AppointmentProductItem[]|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param AppointmentProductItem[]|null $items
     * @return AppointmentProductItem
     */
    public function setItems(?array $items): AppointmentProductItem
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return Appointment|null
     */
    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    /**
     * @param Appointment|null $appointment
     * @return AppointmentProductItem
     */
    public function setAppointment(?Appointment $appointment): self
    {
        $this->appointment = $appointment;

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
     * @return AppointmentProductItem
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
     * @return AppointmentProductItem
     */
    public function setPriceWithCharge(?float $priceWithCharge): AppointmentProductItem
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
     * @return AppointmentProductItem
     */
    public function setStock(?Stock $stock): AppointmentProductItem
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
