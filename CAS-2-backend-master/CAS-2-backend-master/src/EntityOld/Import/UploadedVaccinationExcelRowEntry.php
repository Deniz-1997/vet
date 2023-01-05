<?php

namespace App\EntityOld\Import;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\EntityOld\Vaccination\Vaccination;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Import\UploadedVaccinationExcelRowEntryRepository")
 * @ORM\Table(name="uploaded_vaccination_excel_row", schema="import")
 */
class UploadedVaccinationExcelRowEntry
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private string $id;

    /**
     * @ORM\Column(name="status_code", type="string", length=20, nullable=false)
     * @var string
     */
    private $statusCode;

    /**
     * @ORM\Column(name="status_msg", type="text", nullable=true)
     * @var string
     */
    private $statusMsg;

    /**
     * @ORM\Column(name="parsed_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @var \DateTime
     */
    private $parsedAt;

    /**
     * @ORM\Column(name="processed_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     * @var \DateTime
     */
    private $processedAt;

    /**
     * @ORM\Column(name="row_number", type="integer", nullable=false)
     * @var int
     */
    private $rowNumber;

    /**
     * @ORM\Column(name="data", type="text", nullable=false)
     * @var string
     */
    private $data;

    /**
     * @ORM\OneToOne(targetEntity="App\EntityOld\Vaccination\Vaccination", cascade={"persist"})
     * @ORM\JoinColumn(name="vaccination_id", referencedColumnName="id", nullable=true)
     */
    private $vaccination;

     /**
     * @ORM\ManyToOne(targetEntity="UploadedVaccinationExcelFileEntry", inversedBy="rows")
     * @ORM\JoinColumn(name="excel_file_id", referencedColumnName="id")
     * @var UploadedVaccinationExcelFileEntry
     */
    private $excelFile;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set statusCode
     *
     * @param string $statusCode
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get statusCode
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set statusMsg
     *
     * @param string $statusMsg
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setStatusMsg($statusMsg)
    {
        $this->statusMsg = $statusMsg;

        return $this;
    }

    /**
     * Get statusMsg
     *
     * @return string
     */
    public function getStatusMsg()
    {
        return $this->statusMsg;
    }

    /**
     * Set parsedAt
     *
     * @param \DateTime $parsedAt
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setParsedAt(\DateTime $parsedAt)
    {
        $this->parsedAt = $parsedAt;

        return $this;
    }

    /**
     * Get parsedAt
     *
     * @return \DateTime
     */
    public function getParsedAt()
    {
        return $this->parsedAt;
    }

    /**
     * Set processedAt
     *
     * @param \DateTime $processedAt
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setProcessedAt($processedAt)
    {
        $this->processedAt = $processedAt;

        return $this;
    }

    /**
     * Get processedAt
     *
     * @return \DateTime
     */
    public function getProcessedAt()
    {
        return $this->processedAt;
    }

    /**
     * Set rowNumber
     *
     * @param integer $rowNumber
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setRowNumber($rowNumber)
    {
        $this->rowNumber = $rowNumber;

        return $this;
    }

    /**
     * Get rowNumber
     *
     * @return integer
     */
    public function getRowNumber()
    {
        return $this->rowNumber;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set excelFile
     *
     * @param UploadedVaccinationExcelFileEntry $excelFile
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setExcelFile(UploadedVaccinationExcelFileEntry $excelFile)
    {
        $this->excelFile = $excelFile;

        return $this;
    }

    /**
     * Get excelFile
     *
     * @return UploadedVaccinationExcelFileEntry
     */
    public function getExcelFile()
    {
        return $this->excelFile;
    }

    /**
     * Set vaccination
     *
     * @param Vaccination $vaccination
     * @return UploadedVaccinationExcelRowEntry
     */
    public function setVaccination(Vaccination $vaccination = null)
    {
        $this->vaccination = $vaccination;

        return $this;
    }

    /**
     * Get vaccination
     *
     * @return Vaccination
     */
    public function getVaccination()
    {
        return $this->vaccination;
    }
}
