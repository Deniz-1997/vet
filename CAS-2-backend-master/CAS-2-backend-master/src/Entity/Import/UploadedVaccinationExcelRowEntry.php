<?php

namespace App\Entity\Import;

use Doctrine\DBAL\Types\StringType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Packages\DBAL\Types\VaccinationUploadStatusEnum;
use App\Entity\Reference\Vaccine\Vaccination;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Import\UploadedVaccinationExcelRowEntryRepository")
 * @ORM\Table(name="uploaded_vaccination_excel_row", schema="import")
 */
class UploadedVaccinationExcelRowEntry
{
    use OrmIdTrait, OrmExternalIdTrait;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\VaccinationUploadStatusEnum", nullable=false, options={"default": VaccinationUploadStatusEnum::STATUS_UPLOADED})
     * @var VaccinationUploadStatusEnum
     */
    private VaccinationUploadStatusEnum $status;

    /**
     * @ORM\Column(name="parsed_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @var \DateTime
     */
    private \DateTime $parsedAt;

    /**
     * @ORM\Column(name="processed_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     * @var \DateTime
     */
    private \DateTime $processedAt;

    /**
     * @ORM\Column(name="row_number", type="integer", nullable=false)
     * @var int
     */
    private int $rowNumber;

    /**
     * @ORM\Column(name="data", type="text", nullable=false)
     * @var string
     */
    private string $data;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Reference\Vaccine\Vaccination")
     * @ORM\JoinColumn(nullable=true)
     * @var Vaccination| null
     */
    private ?Vaccination $vaccination = null;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Import\UploadedVaccinationExcelFileEntry", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     * @var UploadedVaccinationExcelFileEntry
     */
    private UploadedVaccinationExcelFileEntry $excelFile;

    /**
     * @ORM\Column(name="status_msg", type="string", length=500, nullable=true)
     * @var string| null
     */
    private ?string $statusMsg = null;

    /**
     * Set statusCode
     *
     * @param VaccinationUploadStatusEnum $status
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setStatus(VaccinationUploadStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get statusCode
     *
     * @return VaccinationUploadStatusEnum
     */
    public function getStatus(): VaccinationUploadStatusEnum
    {
        return $this->status;
    }

    /**
     * Set parsedAt
     *
     * @param \DateTime $parsedAt
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setParsedAt(\DateTime $parsedAt): self
    {
        $this->parsedAt = $parsedAt;

        return $this;
    }

    /**
     * Get parsedAt
     *
     * @return \DateTime
     */
    public function getParsedAt(): \DateTime
    {
        return $this->parsedAt;
    }

    /**
     * Set processedAt
     *
     * @param \DateTime $processedAt
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setProcessedAt(\DateTime $processedAt): self
    {
        $this->processedAt = $processedAt;

        return $this;
    }

    /**
     * Get processedAt
     *
     * @return \DateTime
     */
    public function getProcessedAt(): \DateTime
    {
        return $this->processedAt;
    }

    /**
     * Set rowNumber
     *
     * @param integer $rowNumber
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setRowNumber(int $rowNumber): self
    {
        $this->rowNumber = $rowNumber;

        return $this;
    }

    /**
     * Get rowNumber
     *
     * @return integer
     */
    public function getRowNumber(): int
    {
        return $this->rowNumber;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Set excelFile
     *
     * @param UploadedVaccinationExcelFileEntry $excelFile
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setExcelFile(UploadedVaccinationExcelFileEntry $excelFile): self
    {
        $this->excelFile = $excelFile;

        return $this;
    }

    /**
     * Get excelFile
     *
     * @return UploadedVaccinationExcelFileEntry
     */
    public function getExcelFile(): UploadedVaccinationExcelFileEntry
    {
        return $this->excelFile;
    }

    /**
     * Set vaccination
     *
     * @param Vaccination $vaccination
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setVaccination(?Vaccination $vaccination): self
    {
        $this->vaccination = $vaccination;

        return $this;
    }

    /**
     * Get vaccination
     *
     * @return Vaccination
     */
    public function getVaccination(): ?Vaccination
    {
        return $this->vaccination;
    }

    /**
     * Set statusMsg
     *
     * @param string| null $statusMsg
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setStatusMsg(?string $statusMsg): self
    {
        $this->statusMsg = $statusMsg;

        return $this;
    }

    /**
     * Get statusMsg
     *
     * @return string
     */
    public function getStatusMsg(): ?string
    {
        return $this->statusMsg;
    }
}
