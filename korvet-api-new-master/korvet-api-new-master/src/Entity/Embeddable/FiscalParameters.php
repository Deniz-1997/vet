<?php

namespace App\Entity\Embeddable;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;

/**
 * @ORM\Embeddable()
 */
class FiscalParameters
{
    /**
     * @var FiscalReceiptStateEnum  Статус чека ККМ
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @ORM\Column(type="App\Packages\DBAL\Types\FiscalReceiptStateEnum", nullable=true, options={"default": FiscalReceiptStateEnum::NEW})
     * @SWG\Property(type="object", ref=@Model(type=FiscalReceiptStateEnum::class))
     */
    private $state;

    /**
     * @var \DateTime|null Дата и время отчета
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="datetime", description="Дата и время отчета")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private  $fiscalDocumentDateTime;

    /**
     * @var integer|null Номер ФД отчета
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="integer", description="Номер ФД отчета")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fiscalDocumentNumber;

    /**
     * @var string|null ФПД отчета
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="string", description="ФПД отчета")
     * @ORM\Column(type="string", nullable=true)
     */
    private $fiscalDocumentSign;

    /**
     * @var integer|null Номер чека в смене
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="integer", description="Номер чека в смене")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fiscalReceiptNumber;

    /**
     * @var integer|null Количество чеков за смену
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="integer", description="Количество чеков за смену")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $receiptsCount;

    /**
     * @var float|null Итог
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="float", description="Итог")
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @var string|null Номер ФН
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="string", description="Номер ФН")
     * @ORM\Column(type="string", nullable=true)
     */
    private $fnNumber;

    /**
     * @var string|null РНМ
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="string", description="РНМ")
     * @ORM\Column(type="string", nullable=true)
     */
    private $registrationNumber;

    /**
     * @var int|null Номер смены
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="integer", description="Номер смены")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shiftNumber;

    /**
     * @var string|null URL ФНС
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(type="string", description="URL ФНС")
     * @ORM\Column(type="string", nullable=true)
     */
    private $fnsUrl;

    /**
     * @return FiscalReceiptStateEnum|null
     */
    public function getState():?FiscalReceiptStateEnum
    {
        return $this->state;
    }

    /**
     * @param \App\Packages\DBAL\Types\FiscalReceiptStateEnum|null $state
     */
    public function setState(?FiscalReceiptStateEnum $state): void
    {
        $this->state = $state;
    }

    /**
     * @return \DateTime|null
     */
    public function getFiscalDocumentDateTime(): ?\DateTime
    {
        return $this->fiscalDocumentDateTime;
    }

    /**
     * @param \DateTime|null $fiscalDocumentDateTime
     */
    public function setFiscalDocumentDateTime(?\DateTime $fiscalDocumentDateTime): void
    {
        $this->fiscalDocumentDateTime = $fiscalDocumentDateTime;
    }

    /**
     * @return int|null
     */
    public function getFiscalDocumentNumber(): ?int
    {
        return $this->fiscalDocumentNumber;
    }

    /**
     * @param int|null $fiscalDocumentNumber
     */
    public function setFiscalDocumentNumber(?int $fiscalDocumentNumber): void
    {
        $this->fiscalDocumentNumber = $fiscalDocumentNumber;
    }

    /**
     * @return string|null
     */
    public function getFiscalDocumentSign(): ?string
    {
        return $this->fiscalDocumentSign;
    }

    /**
     * @param string|null $fiscalDocumentSign
     */
    public function setFiscalDocumentSign(?string $fiscalDocumentSign): void
    {
        $this->fiscalDocumentSign = $fiscalDocumentSign;
    }

    /**
     * @return int|null
     */
    public function getFiscalReceiptNumber(): ?int
    {
        return $this->fiscalReceiptNumber;
    }

    /**
     * @param int|null $fiscalReceiptNumber
     */
    public function setFiscalReceiptNumber(?int $fiscalReceiptNumber): void
    {
        $this->fiscalReceiptNumber = $fiscalReceiptNumber;
    }

    /**
     * @return int|null
     */
    public function getReceiptsCount(): ?int
    {
        return $this->receiptsCount;
    }

    /**
     * @param int|null $receiptsCount
     */
    public function setReceiptsCount(?int $receiptsCount): void
    {
        $this->receiptsCount = $receiptsCount;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal(?float $total): void
    {
        $this->total = $total;
    }

    /**
     * @return string|null
     */
    public function getFnNumber(): ?string
    {
        return $this->fnNumber;
    }

    /**
     * @param string|null $fnNumber
     */
    public function setFnNumber(?string $fnNumber): void
    {
        $this->fnNumber = $fnNumber;
    }

    /**
     * @return string|null
     */
    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    /**
     * @param string|null $registrationNumber
     */
    public function setRegistrationNumber(?string $registrationNumber): void
    {
        $this->registrationNumber = $registrationNumber;
    }

    /**
     * @return int|null
     */
    public function getShiftNumber(): ?int
    {
        return $this->shiftNumber;
    }

    /**
     * @param int|null $shiftNumber
     */
    public function setShiftNumber(?int $shiftNumber): void
    {
        $this->shiftNumber = $shiftNumber;
    }

    /**
     * @return string|null
     */
    public function getFnsUrl(): ?string
    {
        return $this->fnsUrl;
    }

    /**
     * @param string|null $fnsUrl
     */
    public function setFnsUrl(?string $fnsUrl): void
    {
        $this->fnsUrl = $fnsUrl;
    }
}
