<?php

namespace App\Entity;

use App\Entity\Cash\CashReceipt;
use App\Interfaces\ProductInterface;
use App\Interfaces\StockInterface;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmNameTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Packages\DBAL\Types\VatRateEnum;
use App\Entity\Embeddable\ProductCode;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity()
 */
class ReceiptItem
{
    use OrmIdTrait, OrmDeletedTrait, OrmNameTrait;

    /**
     * @var CashReceipt Кассовый чек
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Cash\CashReceipt", fetch="EAGER", inversedBy="items")
     * @SWG\Property(description="Кассовый чек")
     * @Assert\NotBlank(message="receipt_item.cash_receipt.not_blank")
     */
    private $cashReceipt;

    /**
     * @AppAssert\PositiveNumber
     * @Assert\GreaterThan(value=0, message="receipt_item.price.empty")
     * @Assert\NotBlank(message="receipt_item.price.empty")
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
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Единица измерения")
     */
    private $measure;

    /**
     * @var PaymentObjectEnum|null Предмет расчета
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\PaymentObjectEnum", nullable=true)
     * @SWG\Property(type="object", ref=@Model(type=PaymentObjectEnum::class), description="Предмет расчета")
     * @Assert\NotBlank(message="receipt_item.type.not_blank")
     */
    private $paymentObject;

    /**
     * @var ProductCode Маркировка товара
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Embeddable\ProductCode", columnPrefix="product_code_")
     * @SWG\Property(ref=@Model(type=App\Entity\Embeddable\ProductCode::class), description="Маркировка товара")
     * @Assert\NotBlank(message="receipt_item.product_code.not_blank")
     */
    private $productCode;

    /**
     * @var VatRateEnum Ставка НДС
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\VatRateEnum", nullable=true)
     * @SWG\Property(ref=@Model(type=App\Packages\DBAL\Types\VatRateEnum::class), description="Ставка НДС")
     */
    private $vatRate;

    /**
     * @var ProductInterface
     * @Groups({"default"})
     */
    private $product;

    /**
     * @var StockInterface
     * @Groups({"default"})
     */
    private $stock;

    /**
     * @var float|null Цена с наценкой
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Цена с наценкой")
     */
    private $priceWithCharge;

    /**
     * @return CashReceipt
     */
    public function getCashReceipt()
    {
        return $this->cashReceipt;
    }

    /**
     * @param CashReceipt $cashReceipt
     */
    public function setCashReceipt(CashReceipt $cashReceipt): void
    {
        $this->cashReceipt = $cashReceipt;
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
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getMeasure(): string
    {
        return $this->measure;
    }

    /**
     * @param string $measure
     */
    public function setMeasure(string $measure): void
    {
        $this->measure = $measure;
    }

    /**
     * @return PaymentObjectEnum|null
     */
    public function getPaymentObject()
    {
        return $this->paymentObject;
    }

    /**
     * @param PaymentObjectEnum|null $paymentObject
     */
    public function setPaymentObject(?PaymentObjectEnum $paymentObject): void
    {
        $this->paymentObject = $paymentObject;
    }

    /**
     * @return ProductCode
     */
    public function getProductCode(): ProductCode
    {
        return $this->productCode;
    }

    /**
     * @param ProductCode $productCode
     */
    public function setProductCode(ProductCode $productCode): void
    {
        $this->productCode = $productCode;
    }

    /**
     * @return VatRateEnum|null
     */
    public function getVatRate():?VatRateEnum
    {
        return $this->vatRate;
    }

    /**
     * @param VatRateEnum $vatRate
     */
    public function setVatRate(?VatRateEnum $vatRate): void
    {
        $this->vatRate = $vatRate;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function setProduct(?ProductInterface $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return StockInterface|null
     */
    public function getStock(): ?StockInterface
    {
        return $this->stock;
    }

    /**
     * @param StockInterface|null $stock
     * @return $this
     */
    public function setStock(?StockInterface $stock): self
    {
        $this->stock = $stock;
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
     * @return ReceiptItem
     */
    public function setPriceWithCharge(?float $priceWithCharge): ReceiptItem
    {
        $this->priceWithCharge = $priceWithCharge;
        return $this;
    }

}
