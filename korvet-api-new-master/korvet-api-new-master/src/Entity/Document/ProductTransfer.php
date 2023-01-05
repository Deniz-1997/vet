<?php

namespace App\Entity\Document;

use App\Entity\Reference\Stock;
use App\Enum\DocumentOperationTypeEnum;
use App\Model\BaseDocument;
use App\Interfaces\DocumentInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Interfaces\DocumentTransferInterface;
use App\Traits\ORMTraits\Complex\ORMDocumentTrait;

/**
 * @ORM\Table(schema="product")
 * @ORM\Entity(repositoryClass="App\Repository\Document\ProductTransferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductTransfer extends BaseDocument implements DocumentTransferInterface
{
    use ORMDocumentTrait;

    /**
     * @var Stock Склад
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Stock")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Stock::class),
     *     description="Склад откуда бедем номенклатуру"
     * )
     */
    private $stockFrom;

    /**
     * @var Stock Склад
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Stock")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Stock::class),
     *     description="Склад куда перемещаем номенклатуру"
     * )
     */
    private $stockTo;

    /**
     * ProductTransfer constructor.
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
        $this->operationType = DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::TRANSFER);
        $this->id = Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->id->toString();
    }

    /**
     * @return Stock
     */
    public function getStockFrom(): Stock
    {
        return $this->stockFrom;
    }

    /**
     * @param Stock $stockFrom
     */
    public function setStockFrom(Stock $stockFrom): void
    {
        $this->stockFrom = $stockFrom;
    }

    /**
     * @return Stock
     */
    public function getStockTo(): Stock
    {
        return $this->stockTo;
    }

    /**
     * @param Stock $stockTo
     */
    public function setStockTo(Stock $stockTo): void
    {
        $this->stockTo = $stockTo;
    }

    public function getOperationType(): ?DocumentOperationTypeEnum
    {
        return DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::TRANSFER); 
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
