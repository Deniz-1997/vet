<?php

namespace App\Interfaces;

use App\Enum\DocumentOperationTypeEnum;
use App\Enum\DocumentStateEnum;
use Doctrine\Common\Collections\Collection;

/**
 * Interface DocumentInterface
 * @package App\Model\Document
 */
interface DocumentInterface
{
    /**
     * @return Collection|DocumentProductInterface[]
     */
    public function getDocumentProducts() : Collection;

    /**
     * @return DocumentStateEnum
     */
    public function getState() : DocumentStateEnum;

    /**
     * @param DocumentStateEnum $state
     * @return DocumentInterface
     */
    public function setState(DocumentStateEnum $state) : DocumentInterface;

    /**
     * @param DocumentProductInterface $documentProduct
     * @return DocumentInterface
     */
    public function addDocumentProduct(DocumentProductInterface $documentProduct): DocumentInterface;

    /**
     * @return string
     */
    public function getUUID() : string;

    /**
     * @return DocumentOperationTypeEnum|null
     */
    public function getOperationType() : ?DocumentOperationTypeEnum;

    /** @return array|null */
    public function getErrors():? array;

    /**
     * @return DocumentInterface
     */
    public function clearErrors(): DocumentInterface;
}
