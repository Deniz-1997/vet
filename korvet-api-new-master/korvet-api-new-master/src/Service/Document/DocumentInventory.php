<?php

namespace App\Service\Document;

use App\Entity\Document\InventoryDocumentProduct;
use App\Enum\DocumentStateEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\ServiceDocumentInterface;
use Doctrine\ORM\PersistentCollection;
use App\Exception\ApiException;
use Exception;

class DocumentInventory extends AbstractDocument
{
    /**
     * @var DocumentInterface
     */
    protected DocumentInterface $document;

    /**
     * @param DocumentStateEnum $state
     * @return bool
     * @throws Exception
     */
    public function setState(DocumentStateEnum $state): bool
    {
        if ($this->document->getState()->code === DocumentStateEnum::DRAFT
            && $state->code === DocumentStateEnum::REGISTERED
        ) {
            /** @var PersistentCollection $productsList */
            $productsList = $this->document->getDocumentProducts();
            if ($productsList->count() == 0) {
                throw new ApiException('document.registered_without_products', 'EMPTY_PRODUCT_LIST', null, 400);
            }
            /** @var InventoryDocumentProduct $documentProduct */
            foreach ($productsList as $documentProduct) {
                /**
                 * 1) quantityActual - текущее значение которое достается со склада, на момент создания документа
                 * 2) quantityAccounting - фактическое кол-во которое вносит пользователь
                 * 3) quantityDifference - недостача / переизбыток. Определяется как quantityActual - quantityAccounting.
                 *
                 */
                //подсчет актуального количесва товара через историю
                $documentProduct->setQuantityActual(
                    $this->service->getQuantityProduct($this->document->getStock(), $documentProduct->getProduct())
                );

                $this->makeHistory($documentProduct, $this->document->getStock(), $documentProduct->getQuantityDifference() * -1);
            }

            $this->document->setState($state);
            $this->setDate();
            //выписываемся из массива хранения в сервисе
            $this->service->removeDocument($this);
            $this->entityManager->flush();

        } elseif ($this->document->getState()->code === DocumentStateEnum::REGISTERED
            && $state->code !== DocumentStateEnum::REGISTERED) {
            $this->removeHistory();
            $this->document->setState($state);
        } else {
            $this->document->setState($state);
        }

        return  true;
    }

    public function getId(): string
    {
        return spl_object_hash($this->document->getStock());
    }


    /**
     * @return ServiceDocumentInterface
     */
    public function clearErrors(): ServiceDocumentInterface
    {
        $this->document->clearErrors();
        return $this;
    }
}
