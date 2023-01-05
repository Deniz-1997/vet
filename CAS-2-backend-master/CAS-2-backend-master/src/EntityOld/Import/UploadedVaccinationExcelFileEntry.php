<?php

namespace App\EntityOld\Import;

use App\EntityOld\User\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\EntityOld\Contractors\SupervisoryAuthority;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImportOld\UploadedVaccinationExcelFileEntryRepository")
 * @ORM\Table(name="uploaded_vaccination_excel_file", schema="import")
 */
class UploadedVaccinationExcelFileEntry
{
    const STATUS_UPLOADED = 'uploaded';
    const STATUS_PENDING = 'pending';
    const STATUS_FINISHED_WITH_ERRORS = 'with_errors';
    const STATUS_FINISHED = 'finished';
    const STATUS_FORMING_RESPONSE = 'forming_response';
    const STATUS_RESPONSE_FORMED = 'response_formed';
    const STATUS_RESPONSE_UPLOADED = 'response_uploaded';
    const STATUS_SYS_ERROR = 'sys_error';

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private string $id;

    /**
     * Файл с исправлениями в записях с ошибками из оригинального (текущего, этого) файла.
     *
     * @ORM\OneToOne(targetEntity="UploadedVaccinationExcelFileEntry")
     * @ORM\JoinColumn(name="fixed_id", referencedColumnName="id")
     * @var UploadedVaccinationExcelFileEntry
     */
    private $fixed;

    /**
     * @ORM\Column(name="hash", type="string", length=32, nullable=false, unique=true)
     * @var string
     */
    private $hash;

    /**
     * @ORM\Column(name="status_code", type="string", length=20, nullable=false)
     * @var string
     */
    private $statusCode;

    /**
     * @ORM\Column(name="status_msg", type="string", length=500, nullable=true)
     * @var string
     */
    private $statusMsg;

    /**
     * @ORM\Column(name="source_name", type="string", length=500, nullable=false)
     * @var string
     */
    private $sourceName;

    /**
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Contractors\SupervisoryAuthority")
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id", nullable=false)
     * @var SupervisoryAuthority
     */
    private $station;

    /**
     * @ORM\Column(name="lock", type="datetime", nullable=true)
     * @var DateTime
     */
    private $lock;

    /**
     * @ORM\Column(name="uploaded_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @var \DateTime
     */
    private $uploadedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\EntityOld\User\User")
     * @ORM\JoinColumn(name="uploaded_by", referencedColumnName="id", nullable=false)
     * @Gedmo\Blameable(on="create")
     */
    private $uploadedBy;

    /**
     * @ORM\OneToMany(targetEntity="UploadedVaccinationExcelRowEntry", mappedBy="excelFile")
     * @var ArrayCollection
     */
    private $rows;

    /**
     * @ORM\Column(name="response_hash", type="string", length=32, nullable=true, unique=true)
     * @var string
     */
    private $responseHash;

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
     * Set fixed
     *
     * @param UploadedVaccinationExcelFileEntry
     * @return UploadedVaccinationExcelFileEntry $entry
     */
    public function setFixed(UploadedVaccinationExcelFileEntry $entry)
    {
        $this->fixed = $entry;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return UploadedVaccinationExcelFileEntry
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set statusCode
     *
     * @param string $statusCode
     * @return UploadedVaccinationExcelFileEntry
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
     * @return UploadedVaccinationExcelFileEntry
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
     * Set sourceName
     *
     * @param string $sourceName
     */
    public function setSourceName(string $sourceName): void
    {
        $this->sourceName = $sourceName;
    }

    /**
     * Get sourceName
     *
     * @return string
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * Set station
     *
     * @param SupervisoryAuthority $station
     */
    public function setStation(SupervisoryAuthority $station): void
    {
        $this->station = $station;
    }

    /**
     * Get station
     *
     * @return SupervisoryAuthority
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * Set lock
     *
     * @param DateTime $lock
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setLock($lock = null)
    {
        $this->lock = $lock;

        return $this;
    }

    /**
     * Get lock
     *
     * @return DateTime
     */
    public function getLock()
    {
        return $this->lock;
    }

    /**
     * Set uploadedAt
     * @param DateTime $uploadedAt
     */
    public function setUploadedAt(DateTime $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }

    /**
     * Get uploadedAt
     *
     * @return DateTime
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * Add row
     *
     * @param UploadedVaccinationExcelRowEntry $row
     *
     * @return UploadedVaccinationExcelFileEntry
     */
    public function addRow(UploadedVaccinationExcelRowEntry $row)
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * Remove row
     *
     * @param UploadedVaccinationExcelRowEntry $row
     */
    public function removeRow(UploadedVaccinationExcelRowEntry $row)
    {
        $this->rows->removeElement($row);
    }

    /**
     * Get rows
     *
     * @return Collection
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Set responseHash
     *
     * @param string $responseHash
     *
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setResponseHash($responseHash)
    {
        $this->responseHash = $responseHash;

        return $this;
    }

    /**
     * Get responseHash
     *
     * @return string
     */
    public function getResponseHash()
    {
        return $this->responseHash;
    }

    /**
     * Set uploadedBy
     * @param User $uploadedBy
     */
    public function setUploadedBy(User $uploadedBy): void
    {
        $this->uploadedBy = $uploadedBy;
    }
}
