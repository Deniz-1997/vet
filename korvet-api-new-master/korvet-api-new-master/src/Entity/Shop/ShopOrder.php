<?php

namespace App\Entity\Shop;

use App\Entity\Cash\CashReceipt;
use App\Entity\Reference\Unit;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Packages\DBAL\Types\PaymentTypeEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentProductInterface;
use App\Enum\DocumentStateEnum;
use App\Enum\DocumentOperationTypeEnum;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Validator\Constraints as Assert;
use App\Model\BaseDocument;
use App\Interfaces\DocumentShopInterface;
use App\Entity\Reference\Stock;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Shop\ShopOrderRepository")
 * @ORM\Table(schema="shop")
 */
class ShopOrder extends BaseDocument implements DocumentShopInterface
{
    use OrmIdTrait, OrmDeletedTrait;

      /**
     * @var ShopProductItem[]
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Shop\ShopProductItem",
     *     mappedBy="shopOrder",
     *     cascade={"persist"},
     *     orphanRemoval=true
     *    )
     */
    protected $documentProducts;

    /**
     * @var \DateTime Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime")
     * @SWG\Property(description="Дата")
     */
    private \DateTime $date;

    /**
     * @var User| null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(description="Пользователь")
     */
    private ?User $user = null;

    /**
     * @var Unit Клиника
     * @Groups({"default"})
     * @SWG\Property(description="Клиника")
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Unit")
     */
    private Unit $unit;

    /**
     * @var CashReceipt Чек ККМ
     * @Groups({"permission.doctor", "permission.appointmentAdmin", "api.appointment"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Cash\CashReceipt")
     * @SWG\Property(description="Чек ККМ")
     */
    private $cashReceipt;

     /**
     * @var PaymentTypeEnum|null
     * @Groups({"default"})
     * @SWG\Property(ref=@Model(type=App\Packages\DBAL\Types\PaymentTypeEnum::class), example="CASH")
     * @ORM\Column(type="App\Packages\DBAL\Types\PaymentTypeEnum", nullable=true)
     */
    private ?PaymentTypeEnum $paymentType = null;

     /**
     * @var Stock Склад
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Stock")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Stock::class),
     *     description="Склад"
     * )
     */
    private Stock $stock;

     /**
     * @var array<ApiException>
     * @Groups({"default"})
     * @ORM\Column(type="array", nullable=true)
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=ApiException::class)) , description="Массив ошибок при попытке проведения документа")
     */
    private $errors;

     /**
     * @var string|UuidInterface|null
     *
     * @Assert\Uuid(versions={4})
     * @SWG\Property(type="string", example="f5e4ff91-c98d-4fd3-a554-7c35592c5280")
     *
     * @ORM\Column(type="uuid", unique=true, nullable=true)
     */
    private $uuid;

    public function __construct()
    {
        $this->documentProducts = new ArrayCollection();
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return null|CashReceipt
     */
    public function getCashReceipt(): ?CashReceipt
    {
        return $this->cashReceipt;
    }

    /**
     * @param CashReceipt|null $cashReceipt
     */
    public function setCashReceipt(?CashReceipt $cashReceipt): void
    {
        $this->cashReceipt = $cashReceipt;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return ShopOrder
     */
    public function setDate(\DateTime $date): ShopOrder
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
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
     * @return Unit
     */
    public function getUnit(): Unit
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     * @return ShopOrder
     */
    public function setUnit(Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeCashReceipt(): self
    {
        if ($this->cashReceipt) {
            $this->cashReceipt->setDeleted(true);
            $this->cashReceipt = null;
        }
        return $this;
    }

     /**
     * @return PaymentTypeEnum|null
     */
    public function getPaymentType(): ?PaymentTypeEnum
    {
        return $this->paymentType;
    }

    /**
     * @param PaymentTypeEnum|null $paymentType
     * @return \App\Entity\Shop\ShopOrder
     */
    public function setPaymentType(?PaymentTypeEnum $paymentType): ShopOrder
    {
        $this->paymentType = $paymentType;
        return $this;
    }

     /**
     * @return string
     * @throws \Exception
     */
    public function getUUID(): string
    {
        if (empty($this->uuid)) {
            $this->uuid = Uuid::uuid4();
        }

        return  $this->uuid->toString();
    }

    /**
     * @return DocumentOperationTypeEnum|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getOperationType() : ?DocumentOperationTypeEnum
    {
        return DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::SHOP);
    }

     /**
     * @return Stock|null
     */
    public function getStock() : ?Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     * @return ShopOrder
     */
    public function setStock(Stock $stock): self
    {
        $this->stock = $stock;
        return $this;
    }
     /**
     * @return array<ApiException>|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array<string> $errors
     * @return self
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

     /**
     * @param string $error
     * @return self
     */
    public function addError(string $error): self
    {
        $this->errors[] = $error;
        return $this;
    }

     /**
     * @return DocumentInterface
     */
    public function clearErrors(): DocumentInterface
    {
        $this->errors = [];
        return $this;
    }

  /**
     * @return Collection|ShopProductItem[]
     */
    public function getDocumentProducts(): Collection
    {
        return $this->documentProducts;
    }

    /**
     * @param \App\Entity\Shop\ShopProductItem $documentProducts
     * @return ShopOrder
     */
    public function addProductItem(ShopProductItem $documentProducts): self
    {
        if (!$this->getDocumentProducts()->contains($documentProducts)) {
            $this->documentProducts[] = $documentProducts;
            $documentProducts->getShopOrder($this);
        }

        return $this;
    }

     /**
     * @return Collection|ShopProductItem[]
     */
    public function getProductItems(): Collection
    {
        return $this->getDocumentProducts();
    }
}
