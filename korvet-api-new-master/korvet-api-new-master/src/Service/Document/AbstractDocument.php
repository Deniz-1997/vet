<?php

namespace App\Service\Document;

use App\Entity\Document\DocumentHistory;
use App\Entity\Document\DocumentProduct;
use App\Entity\Document\InventoryDocumentProduct;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Entity\User\User;
use App\Enum\DocumentOperationTypeEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentProductInterface;
use App\Interfaces\ServiceDocumentInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class AbstractDocument
 * @package App\Service\Document
 */
abstract class AbstractDocument implements ServiceDocumentInterface
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $entityManager;

    /** @var DocumentService */
    protected DocumentService $service;

    /** @var DocumentInterface */
    protected DocumentInterface $document;

    /**
     * Document constructor.
     * @param DocumentInterface $document
     * @param EntityManagerInterface $entityManager
     * @param DocumentService $service
     */
    public function __construct(DocumentInterface $document, EntityManagerInterface $entityManager, DocumentService $service) {
        $this->document = $document;
        $this->entityManager = $entityManager;
        $this->service = $service;
    }

    /**
     * @return DocumentInterface
     */
    public function getDocument(): DocumentInterface
    {
        return  $this->document;
    }

    /**
     * @param string $error
     * @return ServiceDocumentInterface
     */
    public function addError(string $error): ServiceDocumentInterface
    {
        $this->document->addError($error);
        return $this;
    }

    /**
     * @return ServiceDocumentInterface
     */
    public function clearErrors(): ServiceDocumentInterface
    {
        $this->document->clearErrors();
        return $this;
    }

    /**
     * @return int
     */
    public function getErrorsCount(): int
    {

        $errors = $this->document->getErrors();
        if (null === $errors) {
            return  0;
        }

        return  count($this->document->getErrors());
    }

    /**
     * @return mixed|void
     */
    public function removeHistory()
    {
        $update = $this->entityManager->createQueryBuilder()
            ->update(DocumentHistory::class, 'h')
            ->set('h.deleted', 'true')
            ->where('h.documentUUID = :uuid')
            ->getQuery();

        $update->setParameter('uuid', $this->document->getUUID())->execute();
    }

    /**
     * @param Product $product
     * @param float $quantity
     * @return $this
     */
    public function addProduct(Product $product, float $quantity): ServiceDocumentInterface
    {
        if ($this->getDocument()->getOperationType()->code === DocumentOperationTypeEnum::INVENTORY) {
            $documentProduct = new InventoryDocumentProduct();
            $documentProduct->setQuantityAccounting($quantity);
        } else {
            $documentProduct = new DocumentProduct();
            $documentProduct->setQuantity($quantity);
        }

        $documentProduct->setProduct($product);
        $this->entityManager->persist($documentProduct);
        $this->document->addDocumentProduct($documentProduct);

        return $this;
    }

    /**
     * @param DocumentProductInterface $documentProduct
     * @param Stock $stock
     * @param float $quantity
     * @throws Exception
     */
    protected function makeHistory(DocumentProductInterface $documentProduct, Stock $stock, float $quantity): void
    {
        $history = new DocumentHistory();
        $history->setProduct($documentProduct->getProduct());
        $history->setQuantity($quantity);
        $history->setStock($stock);
        $history->setDocumentUUID($this->document->getUUID());
        $history->setOperationType($this->document->getOperationType());
        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }

    /**
     * @return DocumentHistory[]|object[]|null
     */
    public function getHistory(): ?array
    {
        return $this->entityManager->getRepository(DocumentHistory::class)->findBy(
            ['documentUUID' => $this->document->getUUID(), 'deleted' => false]
        );
    }

    /**
     * @param User|null $editor
     * @return self
     */
    public function setEditor(?User $editor): self
    {
        if (method_exists($this->document, 'setEditor')) {
            $this->document->setEditor($editor);
        }
        return $this;
    }

    /**
     * @param User|null $creator
     * @return self
     */
    public function setCreator(?User $creator): self
    {
        if (method_exists($this->document, 'setCreator')) {
            $this->document->setCreator($creator);
        }
        return $this;
    }

    /**
     * устанавливаем дату регистрации документа
     * @throws Exception
     * @return self
     */
    public function setDate(): AbstractDocument
    {
        //setDateEnd
        if (method_exists($this->document, 'setDateEnd')) {
            $date = $this->getDocument()->getDateEnd();
            $this->document->setDateEnd($date ?? new DateTime());
        } elseif (method_exists($this->document, 'setDate')) {
            $date = $this->document->getDate();
            $this->document->setDate($date ?? new DateTime());
        }

        return $this;
    }

    /**
     * получаем дату регистрации документа
     * @return DateTime|null
     */
    public function getDate() : ?DateTime
    {
        if (method_exists($this->document, 'getDateEnd')) {
             return $this->document->getDateEnd();
        } elseif (method_exists($this->document, 'setDate')) {
            return $this->document->getDate();
        }

        return null;
    }

}
