<?php

namespace App\Entity\Document;

use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Exception\ApiException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Document\DocumentHistoryRepository")
 * @ORM\Table(indexes={
 *      @ORM\Index(columns={"product_id", "stock_id"}),
 *      @ORM\Index(columns={"document_uuid"})
 * })
 */
class DocumentHistory
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @ORM\Column(type="datetime")
     * @SWG\Property(type="string", description="Дата, на которую происходит регистрация истории в системе")
     */
    private  $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false)
     * @SWG\Property(
     *     ref=@Model(type=App\Entity\Reference\Product::class),
     *     description="Номенклатура"
     * )
     */
    private  $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Stock")
     * @SWG\Property(
     *     ref=@Model(type=App\Entity\Reference\Stock::class),
     *     description="Склад"
     * )
     */
    private  $stock;

    /**
     * @ORM\Column(type="float")
     * @SWG\Property(type="float", description="Количество едениц номенклатуры")
     */
    private  $quantity;

    /**
     * @ORM\Column(type="App\Enum\DocumentOperationTypeEnum")
     * @SWG\Property(ref=@Model(type=App\Enum\DocumentOperationTypeEnum::class), description="Тип операции")
     */
    private $operationType;

    /**
     * @var string|UuidInterface
     * @Assert\Uuid(versions={4})
     * @ORM\Column(type="uuid", unique=false, nullable=false)
     *
     * @SWG\Property(type="string", example="f5e4ff91-c98d-4fd3-a554-7c35592c5280", description="Идентификатор документа")
     */
    private $documentUUID;

    /**
     * DocumentHistory constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

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
     * @return $this
     * @throws ApiException
     */
    public function setProduct(?Product $product): self
    {

        try {
            if ($product->getPaymentObject()->code !== PaymentObjectEnum::COMMODITY) {
                throw new ApiException('Номенклатура не является товаром! ID:' . $product->getId());
            }
        } catch (\TypeError $e) {
            throw new ApiException('Номенклатура не является товаром! ID:' . $product->getId());
        }

        $this->product = $product;
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
     * @return $this
     */
    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

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
     * @param float $quantity
     * @return $this
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * @param $operationType
     * @return $this
     */
    public function setOperationType($operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentUUID(): ?string
    {
        return $this->documentUUID;
    }

    /**
     * @param string $documentUUID
     * @return $this
     */
    public function setDocumentUUID(string $documentUUID): self
    {
        $this->documentUUID = $documentUUID;

        return $this;
    }
}
