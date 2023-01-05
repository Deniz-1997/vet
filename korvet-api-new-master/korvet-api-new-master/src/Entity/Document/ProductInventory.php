<?php

namespace App\Entity\Document;

use App\Entity\Reference\Stock;
use App\Enum\DocumentOperationTypeEnum;
use App\Enum\DocumentStateEnum;
use App\Model\BaseDocument;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\ORMDocumentTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;

/**
 * Class ProductInventory
 * @ORM\Entity(repositoryClass="App\Repository\Document\ProductInventoryRepository")
 * @ORM\Table("product_inventory", schema="product")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductInventory extends BaseDocument
{
    use ORMDocumentTrait, OrmDeletedTrait;

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
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\InventoryDocumentProduct", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(
     *      schema="product",
     *      joinColumns={@ORM\JoinColumn(referencedColumnName="number")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_product_id", referencedColumnName="id")}
     * )
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Document\InventoryDocumentProduct::class),
     *     description="Документ продукта"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $documentProducts;

    /**
     * @var DocumentOperationTypeEnum|null
     */
    protected ?DocumentOperationTypeEnum $operationType;

    /**
     * BaseDocument constructor.
     * @throws DBALException
     */
    public function __construct()
    {
        parent::__construct();
        $this->documentProducts = new ArrayCollection();
        $this->state = clone DocumentStateEnum::getItem(DocumentStateEnum::DRAFT);
    }

    /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     * @return ProductInventory
     */
    public function setStock(Stock $stock): self
    {
        $this->stock = $stock;
        return $this;
    }


    /**
     * @param DocumentProductInterface $documentProduct
     * @return ProductInventory
     */
    public function addDocumentProduct(DocumentProductInterface $documentProduct): DocumentInterface
    {
        if (! $this->documentProducts->contains($documentProduct)) {
            $this->documentProducts[] = $documentProduct;
        }

        return $this;
    }

    /**
     * @param DocumentOperationTypeEnum|null $operationType
     * @return ProductInventory
     */
    public function setOperationType(?DocumentOperationTypeEnum $operationType): self
    {
        $this->operationType = $operationType;
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
     * @throws DBALException
     */
    public function getOperationType(): ?DocumentOperationTypeEnum
    {
        return DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::INVENTORY);
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
