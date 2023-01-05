<?php

namespace App\Interfaces;

use App\Entity\Document\DocumentHistory;
use App\Entity\Reference\Product;
use App\Enum\DocumentStateEnum;
use App\Interfaces\DocumentInterface;

/**
 * Interface ServiceDocumentInterface
 * @package App\Service\Document
 */
interface ServiceDocumentInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param DocumentStateEnum $state
     * @return bool
     */
    public function setState(DocumentStateEnum $state): bool;

    /**
     * @param Product $product
     * @param float $quantity
     * @return ServiceDocumentInterface
     */
    public function addProduct(Product $product, float $quantity): ServiceDocumentInterface;

    /**
     * @param string $error
     * @return ServiceDocumentInterface
     */
    public function addError(string $error): ServiceDocumentInterface;

    /**
     * @return ServiceDocumentInterface
     */
    public function clearErrors(): ServiceDocumentInterface;

    /**
     * @return mixed
     */
    public function removeHistory();

    /**
     * @return DocumentHistory[]|object[]|null
     */
    public function getHistory(): ?array;

    /**
     * @return DocumentInterface
     */
    public function getDocument(): DocumentInterface;
}
