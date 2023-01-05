<?php


namespace App\Service\Document;


use App\Entity\Reference\Appointment\AppointmentProductItem;
use App\Enum\DocumentStateEnum;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\ServiceDocumentInterface;
use Exception;

class DocumentAppointment extends AbstractDocument
{
    /** @var DocumentInterface */
    protected DocumentInterface $document;

    /**
     * @param DocumentStateEnum $state
     * @return bool
     * @throws Exception
     */
    public function setState(DocumentStateEnum $state): bool
    {
        if (($this->document->getState()->code === DocumentStateEnum::DRAFT
            || $this->document->getState()->code === DocumentStateEnum::ERROR)
            && $state->code === DocumentStateEnum::REGISTERED
        ) {

            /** @var AppointmentProductItem $documentProduct */
            foreach ($this->document->getDocumentProducts() as $documentProduct) {
                if (null === $documentProduct->getStock() || $documentProduct->getProduct()->getPaymentObject()->code !== PaymentObjectEnum::COMMODITY) {
                    continue;
                }
                $this->makeHistory($documentProduct, $documentProduct->getStock(), $documentProduct->getQuantity() * -1);
            }

            $this->document->setState($state);
            $this->setDate();
            //выписываемся из массива хранения в сервисе
            $this->service->removeDocument($this);
            $this->entityManager->flush();
            
        } elseif ($this->document->getState()->code === DocumentStateEnum::REGISTERED && $state->code !== DocumentStateEnum::REGISTERED) {
            $this->removeHistory();
            $this->document->setState($state);
        } else {
            $this->document->setState($state);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return spl_object_hash($this->document);
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
