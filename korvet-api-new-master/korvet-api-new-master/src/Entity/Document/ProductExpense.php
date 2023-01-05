<?php

namespace App\Entity\Document;

use App\Entity\Reference\Stock;
use App\Enum\DocumentOperationTypeEnum;
use App\Interfaces\DocumentExpenseInterface;
use App\Interfaces\DocumentInterface;
use App\Model\BaseDocument;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\ORMDocumentTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Document\ProductExpenseRepository")
 * @ORM\Table("product_expense", schema="product")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductExpense extends BaseDocument implements DocumentExpenseInterface
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
     * ProductExpense constructor.
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
     * @return ProductExpense
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
        return DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::EXPENSE);
    }

    /**
     * @return DocumentInterface
     */
    public function clearErrors(): DocumentInterface
    {
        $this->setErrors(null);
        return $this;
    }

}
