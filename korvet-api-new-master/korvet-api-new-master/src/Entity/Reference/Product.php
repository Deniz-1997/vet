<?php

namespace App\Entity\Reference;

use App\Interfaces\ProductInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Traits\ORMTraits\OrmActiveTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Packages\DBAL\Types\VatRateEnum;
use App\Entity\Embeddable\ProductCode;
use App\Entity\ProductStock;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Table(schema="product")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\ProductRepository")
 */
class Product implements ProductInterface
{
    use OrmIdTrait, OrmDeletedTrait, OrmActiveTrait;

    /**
     * @var string Наименование
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Column(type="text", nullable=false, options={"default": ""})
     * @SWG\Property(description="Наименование", type="string")
     * @Assert\NotBlank(message="name.not_blank")
     */
    private string $name;

    /**
     * @var string Идентификатор
     *
     * @Groups({
     *     "default",
     *     "registration",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Идентификатор", type="string")
     */
    private $externalId;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Тип номенклатуры")
     */
    private $itemType;

    /**
     * @var float|null
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Количество")
     */
    private $quantity = 0;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(type="string", description="Полное наименование")
     */
    private $fullName;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(type="string", description="Код товара")
     */
    private $code;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(type="string", description="Артикль товара")
     */
    private $article;

    /**
     * @var DateTime|null
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(type="datetime", description="Дата")
     */
    private $date;

    /**
     * @var Unit|null Подразделение
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Unit", inversedBy="products")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Unit::class),
     *     description="Подразделение"
     * )
     */
    private $unit;

    /**
     * @AppAssert\PositiveNumber
     * @var float
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Розничная цена")
     */
    private $price;

    /**
     * @var PaymentObjectEnum Предмет расчета
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
     * @SWG\Property(ref=@Model(
     *     type=App\Entity\Embeddable\ProductCode::class),
     *     description="Маркировка товара"
     * )
     * @Assert\NotBlank(message="receipt_item.product_code.not_blank")
     */
    private $productCode;

    /**
     * @var VatRateEnum Ставка НДС
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\VatRateEnum", nullable=true)
     * @SWG\Property(ref=@Model(
     *     type=App\Packages\DBAL\Types\VatRateEnum::class),
     *     description="Ставка НДС"
     * )
     */
    private $vatRate;

    /**
     * @var bool Признак импорта строки из файла
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean", description="Признак импорта строки из файла")
     */
    private $imported = false;

    /**
     * @var bool Признак наличия цены
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean", description="Признак наличия цены")
     */
    private $existPrice = false;

    /**
     * @var bool Признак наличия кол-ва
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean", description="Признак наличия кол-ва")
     */
    private $existQuantity = false;

    /**
     * @var ProductStock[]|ArrayCollection Остатки на складах
     * @ORM\OneToMany(targetEntity="App\Entity\ProductStock", mappedBy="product",  cascade={"persist"})
     * @Groups({"default"})
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=ProductStock::class), description="Остатки на складах"))
     */
    private $productStock;

    /**
     * @var ArrayCollection
     */
    private ?ArrayCollection $appointmentTemplate = null;

    /**
     * @var Manufacturer|null Производство
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\Manufacturer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manufacturer;

    /**
     * @var ReleaseForm|null Форма выпуска
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\ReleaseForm")
     * @ORM\JoinColumn(nullable=true)
     */
    private $releaseForm;

    /**
     * @var CategoryNomenclature|null Категории
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\CategoryNomenclature")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categoryNomenclature;

    /**
     * @var Countries|null Страна
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\Countries")
     * @ORM\JoinColumn(nullable=true)
     */
    private $countries;

    /**
     * @var MeasurementUnits| null Еденицы измерения
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\MeasurementUnits")
     * @ORM\JoinColumn (nullable=true)
     */
    private $measurementUnits;

    /**
     * @var bool Признак бюджетного препарата
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean", description="Признак бюджетного препарата")
     */

    private  $budgetDrug = false;

    /**
     * @var Disease[] Заболевания
     * @Groups ({"default"})
     * @ORM\ManyToMany (targetEntity="App\Entity\Reference\Disease")
     * @ORM\JoinColumn(nullable=true)
     */
    private $disease;



    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->appointmentTemplate = new ArrayCollection();
        $this->productStock = new ArrayCollection();
        $this->disease = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getItemType(): ?string
    {
        return $this->itemType;
    }

    /**
     * @param string|null $itemType
     * @return Product
     */
    public function setItemType(?string $itemType): Product
    {
        $this->itemType = $itemType;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     * @return Product
     */
    public function setQuantity(?float $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string|null $fullName
     * @return Product
     */
    public function setFullName(?string $fullName): Product
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return Unit|null
     */
    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    /**
     * @param Unit|null $unit
     * @return Product
     */
    public function setUnit(?Unit $unit): Product
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param null|string $externalId
     * @return $this
     */
    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSetExternalId(): bool
    {
        return !($this->externalId === null || $this->externalId === '');
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
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;
        $this->setExistPrice(true);
        return $this;
    }

    /**
     * @return PaymentObjectEnum
     */
    public function getPaymentObject(): PaymentObjectEnum
    {
        return $this->paymentObject;
    }

    /**
     * @param PaymentObjectEnum $paymentObject
     * @return Product
     */
    public function setPaymentObject(PaymentObjectEnum $paymentObject): Product
    {
        $this->paymentObject = $paymentObject;
        return $this;
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
     * @return Product
     */
    public function setProductCode(ProductCode $productCode): Product
    {
        $this->productCode = $productCode;
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
     * @return Product
     */
    public function setVatRate(VatRateEnum $vatRate): Product
    {
        $this->vatRate = $vatRate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isImported(): bool
    {
        return $this->imported;
    }

    /**
     * @param bool $imported
     * @return Product
     */
    public function setImported(bool $imported): Product
    {
        $this->imported = $imported;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExistPrice(): bool
    {
        return $this->existPrice;
    }

    /**
     * @param bool $existPrice
     * @return Product
     */
    public function setExistPrice(bool $existPrice): Product
    {
        $this->existPrice = $existPrice;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExistQuantity(): bool
    {
        return $this->existQuantity;
    }

    /**
     * @param bool $existQuantity
     * @return Product
     */
    public function setExistQuantity(bool $existQuantity): Product
    {
        $this->existQuantity = $existQuantity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getArticle(): ?string
    {
        return $this->article;
    }

    /**
     * @param string|null $article
     *
     * @return $this
     */
    public function setArticle(?string $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     * @return Product
     */
    public function setDate(?DateTime $date): Product
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return ProductStock[]|ArrayCollection
     */
    public function getProductStock()
    {
        /** @var ProductStock $productStock */
        foreach ($this->productStock as $productStock) {
            $stock = $productStock->getStock();
            if ($stock && $stock->isDeleted()) {
                $this->productStock->removeElement($productStock);
            }
        }

        return new ArrayCollection($this->productStock->getValues());
    }

    /**
     * @param ProductStock $productStock
     *
     * @return $this
     */
    public function addProductStock(ProductStock $productStock) : self
    {
        if (!$this->productStock->contains($productStock)) {
            $this->productStock->add($productStock);
            $productStock->setProduct($this);
        }

        return $this;
    }

    /**
     * @param array|null $productStocks
     * @return $this
     * */
    public function setProductStock(?array $productStocks) : self
    {
        foreach ($productStocks as $productStock) {
            $this->addProductStock($productStock);
        }
        return  $this;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getProductStock() !== null) {
            $stocks = [];
            /** @var ProductStock $productStock*/
            foreach ($this->getProductStock() as $productStock) {
                $id = $productStock->getStock()->getId();
                if (in_array($id, $stocks)) {
                    $stockExternalId = $productStock->getStock()->getExternalId();
                    $productExternalId = $productStock->getProduct()->getExternalId();
                    $context->buildViolation("Склад $stockExternalId уже добавлен к номенклатуре $productExternalId")
                        ->atPath('productStock')
                        ->addViolation();
                    break;
                }

                $stocks[] = $id;
            }
        }
    }


    /**
     * @return Manufacturer
     */
    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }


    /**
     * @param Manufacturer $manufacturer
     * @return $this
     */
    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }


    /**
     * @return ReleaseForm
     */
    public function getReleaseForm(): ?ReleaseForm
    {
        return $this->releaseForm;
    }


    /**
     * @param ReleaseForm $releaseForm
     * @return $this
     */
    public function setReleaseForm(?ReleaseForm $releaseForm):self
    {
        $this->releaseForm = $releaseForm;
        return $this;
    }


    /**
     * @return CategoryNomenclature
     */
    public function getCategoryNomenclature(): ?CategoryNomenclature
    {
        return $this->categoryNomenclature;
    }


    /**
     * @param CategoryNomenclature $categoryNomenclature
     * @return $this
     */
    public function setCategoryNomenclature(?CategoryNomenclature $categoryNomenclature): self
    {
        $this->categoryNomenclature = $categoryNomenclature;
        return $this;
    }
    /**
     * @return Countries|null
     */
    public function getCountries(): ?Countries
    {
        return $this->countries;
    }


    /**
     * @param Countries|null $countries
     * @return $this
     */
    public function setCountries(?Countries $countries): self
    {
        $this->countries = $countries;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getDisease(): Collection
    {
        return $this->disease;
    }


    /**
     * @param Disease $disease
     * @return Product
     */
    public function addDisease(Disease $disease): self
    {
        if (!$this->disease->contains($disease)) {
            $this->disease[] = $disease;
        }

        return $this;
    }

    /**
     * @param Disease $disease
     * @return Product
     */
    public function removeDisease(Disease $disease): self
    {
        if ($this->disease->contains($disease)) {
            $this->disease->removeElement($disease);
        }

        return $this;
    }

    public function getMeasurementUnits(): ?MeasurementUnits
    {
        return $this->measurementUnits;
    }

    public function setMeasurementUnits(?MeasurementUnits $measurementUnits): self
    {
        $this->measurementUnits = $measurementUnits;
        return $this;
    }

    /**
     * @return bool
     */
    public function getBudgetDrug(): bool
    {
        return ($this->budgetDrug === null ? $this->budgetDrug = false : $this->budgetDrug);
    }

    /**
     * @param bool $budgetDrug
     * @return $this
     */
    public function setBudgetDrug(bool $budgetDrug): self
    {
        $this->budgetDrug = $budgetDrug;
        return $this;
    }
}
