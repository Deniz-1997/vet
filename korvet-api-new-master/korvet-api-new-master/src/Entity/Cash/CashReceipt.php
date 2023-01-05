<?php

namespace App\Entity\Cash;

use App\Entity\ReceiptItem;
use App\Entity\Shift;
use App\Interfaces\CashierUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Packages\DBAL\Types\CorrectionTypeEnum;
use App\Packages\DBAL\Types\PaymentMethodEnum;
use App\Packages\DBAL\Types\PaymentTypeEnum;
use App\Packages\DBAL\Types\ReceiptDeliveryTypeEnum;
use App\Packages\DBAL\Types\TaxationTypeEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\Reference\CashRegister;
use DateTime;

/**
 * @ORM\Table(schema="cash")
 * @ORM\Entity()
 */
class CashReceipt
{
    use OrmIdTrait, OrmCreatedAtTrait, OrmDeletedTrait;

    /**
     * @var CashRegister ККМ
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\CashRegister", fetch="EAGER")
     * @ORM\JoinColumn(name="cash_register_id", referencedColumnName="id", nullable=false)
     * @SWG\Property(description="ККМ")
     * @Assert\NotBlank(message="cash_receipt.cash_register.not_blank")
     */
    private CashRegister $cashRegister;

    /**
     * @var CashierUserInterface|null
     */
    private ?CashierUserInterface $creator;

    /**
     * @var CashierUserInterface|null
     */
    private ?CashierUserInterface $cashier;

    /**
     * @Groups({"detail"})
     * @var CashReceipt|null
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToOne(targetEntity="App\Entity\Cash\CashReceipt")
     */
    private ?CashReceipt $baseDocument;

    /**
     * @var CashReceiptTypeEnum
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\CashReceiptTypeEnum", nullable=false)
     * @SWG\Property(type="object", ref=@Model(type=CashReceiptTypeEnum::class))
     * @Assert\NotBlank(message="cash_receipt.type.not_blank")
     */
    private CashReceiptTypeEnum $type;

    /**
     * @var TaxationTypeEnum
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\TaxationTypeEnum", nullable=false)
     * @SWG\Property(type="object", ref=@Model(type=TaxationTypeEnum::class))
     */
    private TaxationTypeEnum $taxationType;

    /**
     * @var ReceiptDeliveryTypeEnum
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\ReceiptDeliveryTypeEnum", nullable=false)
     * @SWG\Property(type="object", ref=@Model(type=ReceiptDeliveryTypeEnum::class))
     * @Assert\NotBlank(message="cash_receipt.delivery_type.not_blank")
     */
    private ReceiptDeliveryTypeEnum $deliveryType;

    /**
     * @var PaymentMethodEnum
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\PaymentMethodEnum", nullable=false)
     * @SWG\Property(type="object", ref=@Model(type=PaymentMethodEnum::class))
     * @Assert\NotBlank(message="cash_receipt.payment_method.not_blank")
     */
    private PaymentMethodEnum $paymentMethod;

    /**
     * @var PaymentTypeEnum
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\PaymentTypeEnum", nullable=false)
     * @Assert\NotBlank(message="cash_receipt.payment_type.not_blank")
     */
    private PaymentTypeEnum $paymentType;

    /**
     * @var float
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private float $total;

    /**
     * @var CorrectionTypeEnum
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\CorrectionTypeEnum", nullable=true)
     * @SWG\Property(type="object", ref=@Model(type=CorrectionTypeEnum::class))
     */
    private CorrectionTypeEnum $correctionType;

    /**
     * @var \DateTime
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTime $correctionBaseDate;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private string $correctionBaseNumber;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private string $correctionDescription;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email(message="cash_receipt.customer_email.not_valid")
     */
    private string $customerEmail;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(pattern="/^\+7[0-9]{10}*$", message="cash_receipt.customer_phone.not_valid")
     */
    private string $customerPhone;

    /**
     * @Assert\Valid()
     * @var ReceiptItem[]
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\ReceiptItem", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="cashReceipt")
     * @Assert\NotBlank(message="cash_receipt.items.not_blank")
     */
    private $items;

    /**
     * @var FiscalParameters Фискальные параметры при закрытии смены
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Embeddable\FiscalParameters", columnPrefix="fiscal_")
     * @SWG\Property(ref=@Model(type=App\Entity\Embeddable\FiscalParameters::class), description="Фискальные параметры")
     */
    private FiscalParameters $fiscal;

    /**
     * @var Shift|null
     * @Groups({"default"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Shift")
     */
    private ?Shift $shift;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTime $startPrintAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="text", nullable=true)
     */
    private DateTime $uuidReceiptMobile;

    /**
     * CashReceipt constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return CashRegister
     */
    public function getCashRegister(): CashRegister
    {
        return $this->cashRegister;
    }

    /**
     * @param CashRegister $cashRegister
     * @return CashReceipt
     */
    public function setCashRegister(CashRegister $cashRegister): self
    {
        $this->cashRegister = $cashRegister;
        return $this;
    }

    /**
     * @return CashierUserInterface|null
     */
    public function getCreator(): ?CashierUserInterface
    {
        return $this->creator;
    }

    /**
     * @param CashierUserInterface $creator
     * @return CashReceipt
     */
    public function setCreator(CashierUserInterface $creator): CashReceipt
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return CashierUserInterface|null
     */
    public function getCashier(): ?CashierUserInterface
    {
        return $this->cashier;
    }

    /**
     * @param CashierUserInterface $cashier
     * @return CashReceipt
     */
    public function setCashier(CashierUserInterface $cashier): CashReceipt
    {
        $this->cashier = $cashier;
        return $this;
    }

    /**
     * @return CashReceiptTypeEnum
     */
    public function getType(): CashReceiptTypeEnum
    {
        return $this->type;
    }

    /**
     * @param CashReceiptTypeEnum $type
     * @return CashReceipt
     */
    public function setType(CashReceiptTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return TaxationTypeEnum|null
     */
    public function getTaxationType():TaxationTypeEnum
    {
        return $this->taxationType;
    }

    /**
     * @param TaxationTypeEnum $taxationType
     * @return CashReceipt
     */
    public function setTaxationType(TaxationTypeEnum $taxationType): CashReceipt
    {
        $this->taxationType = $taxationType;
        return $this;
    }

    /**
     * @return ReceiptDeliveryTypeEnum
     */
    public function getDeliveryType():ReceiptDeliveryTypeEnum
    {
        return $this->deliveryType;
    }

    /**
     * @param ReceiptDeliveryTypeEnum $deliveryType
     * @return CashReceipt
     */
    public function setDeliveryType(ReceiptDeliveryTypeEnum $deliveryType): self
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    /**
     * @return PaymentMethodEnum
     */
    public function getPaymentMethod(): PaymentMethodEnum
    {
        return $this->paymentMethod;
    }

    /**
     * @param PaymentMethodEnum $paymentMethod
     * @return CashReceipt
     */
    public function setPaymentMethod(PaymentMethodEnum $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return PaymentTypeEnum
     */
    public function getPaymentType(): PaymentTypeEnum
    {
        return $this->paymentType;
    }

    /**
     * @param PaymentTypeEnum $paymentType
     * @return CashReceipt
     */
    public function setPaymentType(PaymentTypeEnum $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total ?? 0;
    }

    /**
     * @return CashReceipt
     */
    public function setTotal($total): self
    {
//        $total = 0;
//        if($this->items) {
//            foreach ($this->items as $item) {
//                /** @var ReceiptItem $item */
//                $total += $item->getAmount();
//            }
//        }

        $this->total = $total;

        return $this;
    }

    /**
     * @return CorrectionTypeEnum|null
     */
    public function getCorrectionType():?CorrectionTypeEnum
    {
        return $this->correctionType;
    }

    /**
     * @param CorrectionTypeEnum $correctionType
     * @return CashReceipt
     */
    public function setCorrectionType(CorrectionTypeEnum $correctionType): self
    {
        $this->correctionType = $correctionType;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCorrectionBaseDate(): ?DateTime
    {
        return $this->correctionBaseDate;
    }

    /**
     * @param \DateTime $correctionBaseDate
     * @return CashReceipt
     */
    public function setCorrectionBaseDate(DateTime $correctionBaseDate): self
    {
        $this->correctionBaseDate = $correctionBaseDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCorrectionBaseNumber(): ?string
    {
        return $this->correctionBaseNumber;
    }

    /**
     * @param string $correctionBaseNumber
     * @return CashReceipt
     */
    public function setCorrectionBaseNumber(string $correctionBaseNumber): self
    {
        $this->correctionBaseNumber = $correctionBaseNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCorrectionDescription(): ?string
    {
        return $this->correctionDescription;
    }

    /**
     * @param string $correctionDescription
     * @return CashReceipt
     */
    public function setCorrectionDescription(string $correctionDescription): self
    {
        $this->correctionDescription = $correctionDescription;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    /**
     * @param string $customerEmail
     * @return CashReceipt
     */
    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    /**
     * @param string $customerPhone
     *
     * @return CashReceipt
     */
    public function setCustomerPhone(string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items ?? [];
    }

    /**
     * @param ReceiptItem[] $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        foreach ($items as $item) {
            $item->setCashReceipt($this);
        }

        $this->items = $items;

        return $this;
    }

    /**
     * @return FiscalParameters|null
     */
    public function getFiscal(): ?FiscalParameters
    {
        return $this->fiscal;
    }

    /**
     * @param FiscalParameters| null $fiscal
     * @return CashReceipt
     */
    public function setFiscal(?FiscalParameters $fiscal): self
    {
        $this->fiscal = $fiscal;
        return $this;
    }

    /**
     * @return Shift|null
     */
    public function getShift(): ?Shift
    {
        return $this->shift;
    }

    /**
     * @param Shift|null $shift
     * @return $this
     */
    public function setShift(?Shift $shift): self
    {
        $this->shift = $shift;

        return $this;
    }

    /**
     * @return CashReceipt|null
     */
    public function getBaseDocument(): ?CashReceipt
    {
        return $this->baseDocument;
    }

    /**
     * @param CashReceipt|null $baseDocument
     * @return $this
     */
    public function setBaseDocument(?CashReceipt $baseDocument): self
    {
        $this->baseDocument = $baseDocument;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStartPrintAt(): ?DateTime
    {
        return $this->startPrintAt;
    }

    /**
     * @param DateTime|null $startPrintAt
     * @return $this
     */
    public function setStartPrintAt(?DateTime $startPrintAt): self
    {
        $this->startPrintAt = $startPrintAt;

        return $this;
    }
}
