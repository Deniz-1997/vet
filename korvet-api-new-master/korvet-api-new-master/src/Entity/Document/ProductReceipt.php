<?php

namespace App\Entity\Document;

use App\Entity\Reference\Stock;
use App\Enum\DocumentOperationTypeEnum;
use App\Model\BaseDocument;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentStockInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\ORMDocumentTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Document\ProductReceiptRepository")
 * @ORM\Table("product_receipt", schema="product")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductReceipt extends BaseDocument implements DocumentStockInterface
{
    use ORMDocumentTrait;

    /**
     * @var Stock Склад
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Stock")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Stock::class),
     *     description="Склад"
     * )
     */
    private $stock;

    /**
     * ProductReceipt constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
        $this->id = Uuid::uuid4();
    }

    /**
     * @return Stock|null
     */
    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     * @return ProductReceipt
     */
    public function setStock(Stock $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->id->toString();
    }

    /**
     * @return DocumentOperationTypeEnum|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getOperationType() : ?DocumentOperationTypeEnum
    {
        return DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::IMPORT);
    }

    /**
     * @return $this|DocumentInterface
     */
    public function clearErrors(): DocumentInterface
    {
        $this->setErrors(null);
        return $this;
    }

}
