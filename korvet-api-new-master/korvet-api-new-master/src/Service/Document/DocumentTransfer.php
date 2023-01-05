<?php

namespace App\Service\Document;


use App\Enum\DocumentStateEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentProductInterface;
use App\Interfaces\ServiceDocumentInterface;
use Doctrine\ORM\PersistentCollection;
use App\Exception\ApiException;

class DocumentTransfer extends AbstractDocument
{
    /** @var DocumentInterface */
    protected DocumentInterface $document;


    /**
     * @param DocumentStateEnum $state
     * @return bool
     * @throws ApiException
     */
    public function setState(DocumentStateEnum $state) : bool
    {
        if ($this->document->getState()->code === DocumentStateEnum::DRAFT
            && $state->code === DocumentStateEnum::REGISTERED
        ) {
            /** @var PersistentCollection $productsList */
            $productsList = $this->document->getDocumentProducts();
            if ($productsList->count() == 0) {
                throw new ApiException('document.registered_without_products', 'EMPTY_PRODUCT_LIST', null, 400);
            }
            /** @var DocumentProductInterface $documentProduct */
            foreach ($this->document->getDocumentProducts() as $documentProduct) {
                //From
                $this->makeHistory($documentProduct, $this->document->getStockFrom(), $documentProduct->getQuantity() * -1);
                //TO
                $this->makeHistory($documentProduct,$this->document->getStockTo(),  $documentProduct->getQuantity());
            }

            $this->document->setState($state);
            $this->setDate();
            //выписываемся из массива хранения в сервисе
            $this->service->removeDocument($this);

        } elseif ($this->document->getState()->code === DocumentStateEnum::REGISTERED
            && $state->code !== DocumentStateEnum::REGISTERED) {
            $this->removeHistory();
            $this->document->setState($state);
        } else {
            $this->document->setState($state);
        }

        return  true;
    }


    /**
     * @return string
     */
    public function getId() : string
    {
        return spl_object_hash($this->document->getStockFrom()) . spl_object_hash($this->document->getStockTo());
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
