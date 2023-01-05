<?php

namespace App\Service\Document;

use App\Entity\Reference\Stock;
use App\Enum\DocumentStateEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentProductInterface;
use App\Interfaces\ServiceDocumentInterface;
use Doctrine\ORM\PersistentCollection;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\ApiException;

/**
 * Class Document
 * @package App\Service\Document
 */
class DocumentShop extends AbstractDocument
{

    /** @var DocumentInterface */
    protected DocumentInterface $document;

    /**
     * @param DocumentStateEnum $state
     * @return bool
     * @throws Exception
     */
    public function setState(DocumentStateEnum $state) : bool
    {
        if ($this->document->getState()->code === DocumentStateEnum::DRAFT
            && $state->code === DocumentStateEnum::REGISTERED
            && !$this->document->getErrors()
        ) {
            /** @var PersistentCollection $productsList */
            $productsList = $this->document->getDocumentProducts();
            if ($productsList->count() == 0) {
                throw new ApiException(
                    'document.registered_without_products',
                    'EMPTY_PRODUCT_LIST',
                    null,
                    Response::HTTP_BAD_REQUEST
                );
            }
            /** @var DocumentProductInterface $documentProduct */
            foreach ($productsList as $documentProduct) {
                $this->makeHistory($documentProduct, $this->document->getStock(), $documentProduct->getQuantity() * -1);
            }

            $this->document->setState($state);
            $this->setDate();
            $this->service->removeDocument($this);
            $this->entityManager->flush();
        } elseif ($this->document->getState()->code === DocumentStateEnum::REGISTERED
            && $state->code !== DocumentStateEnum::REGISTERED) {
            $this->removeHistory();
            $this->document->setState($state);
        } elseif ($state->code === DocumentStateEnum::REGISTERED && $this->document->getErrors()) {
            $errors = $this->document->getErrors();
            throw new ApiException($errors[0], $errors[0], null, Response::HTTP_BAD_REQUEST);
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
        return spl_object_hash($this->document->getStock());
    }

    /**
     * @return Stock
     */
    public function getStock() : Stock
    {
        return $this->document->getStock();
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
