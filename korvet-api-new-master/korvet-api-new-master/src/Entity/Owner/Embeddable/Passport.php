<?php

namespace App\Entity\Owner\Embeddable;


use App\Enum\DocumentTypeEnum;
use App\Validator\Constraints as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 * @AppAssert\IsNumberAndSeriesCorrect()
 */
class Passport
{
    /**
     * @var string|null Серия
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Серия")
     */
    private $series;

    /**
     * @var string|null Номер
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Номер")
     */
    private $number;

    /**
     * @var string|null Кем выдан
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Кем выдан")
     */
    private $issuer;

    /**
     * @var \DateTime|null Дата выдачи
     * @Groups({"default"})
     * @ORM\Column(type="date", nullable=true)
     * @SWG\Property(type="string", description="Дата выдачи", format="date", example="31.12.2017")
     */
    private $dateOfIssue;

    /**
     * @var string|null Код подразделения
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Код подразделения")
     */
    private $issuerCode;

    /**
     * @var DocumentTypeEnum|null Вид документа
     * @Groups({"default"})
     * @ORM\Column(type="App\Enum\DocumentTypeEnum", nullable=true, options={"default": DocumentTypeEnum::RF_CITIZEN_PASSPORT})
     * @SWG\Property(ref=@Model(type=App\Enum\DocumentTypeEnum::class), example="RF_CITIZEN_PASSPORT")
     */
    private $documentType;

    /**
     * @return null|string
     */
    public function getSeries(): ?string
    {
        return $this->series;
    }

    /**
     * @param null|string $series
     * @return Passport
     */
    public function setSeries(?string $series): Passport
    {
        $this->series = $series;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param null|string $number
     * @return Passport
     */
    public function setNumber(?string $number): Passport
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    /**
     * @param null|string $issuer
     * @return Passport
     */
    public function setIssuer(?string $issuer): Passport
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateOfIssue(): ?\DateTime
    {
        return $this->dateOfIssue;
    }

    /**
     * @param \DateTime|null $dateOfIssue
     * @return Passport
     */
    public function setDateOfIssue(?\DateTime $dateOfIssue): Passport
    {
        $this->dateOfIssue = $dateOfIssue;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getIssuerCode(): ?string
    {
        return $this->issuerCode;
    }

    /**
     * @param null|string $issuerCode
     * @return Passport
     */
    public function setIssuerCode(?string $issuerCode): Passport
    {
        $this->issuerCode = $issuerCode;
        return $this;
    }

    /**
     * @return DocumentTypeEnum|null
     */
    public function getDocumentType(): ?DocumentTypeEnum
    {
        return $this->documentType;
    }

    /**
     * @param DocumentTypeEnum|null $documentType
     * @return Passport
     */
    public function setDocumentType(?DocumentTypeEnum $documentType): Passport
    {
        $this->documentType = $documentType;
        return $this;
    }
}
