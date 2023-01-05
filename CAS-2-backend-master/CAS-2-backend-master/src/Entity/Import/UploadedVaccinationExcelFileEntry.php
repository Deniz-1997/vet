<?php

namespace App\Entity\Import;

use App\Entity\User\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Entity\Reference\Station;
use App\Packages\DBAL\Types\VaccinationUploadStatusEnum;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Import\UploadedVaccinationExcelFileEntryRepository")
 * @ORM\Table(name="uploaded_vaccination_excel_file", schema="import")
 */
class UploadedVaccinationExcelFileEntry
{
    use OrmIdTrait, OrmExternalIdTrait;

    /**
     * Файл с исправлениями в записях с ошибками из оригинального (текущего, этого) файла.
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="UploadedVaccinationExcelFileEntry")
     * @ORM\JoinColumn(name="fixed_id", referencedColumnName="id")
     * @var UploadedVaccinationExcelFileEntry
     */
    private UploadedVaccinationExcelFileEntry $fixed;

    /**
     * @Groups({"default"})
     * @ORM\Column(name="hash", type="string", length=32, nullable=false, unique=true)
     * @var string
     */
    private string $hash;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\VaccinationUploadStatusEnum", nullable=false, options={"default": VaccinationUploadStatusEnum::STATUS_UPLOADED})
     * @var VaccinationUploadStatusEnum
     */
    private VaccinationUploadStatusEnum $status;

    /**
     * @Groups({"default"})
     * @ORM\Column(name="status_msg", type="string", length=500, nullable=true)
     * @var string | null
     */
    private ?string $statusMsg = null;

    /**
     * @Groups({"default"})
     * @ORM\Column(name="source_name", type="string", length=500, nullable=false)
     * @var string
     */
    private string $sourceName;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Station")
     * @ORM\JoinColumn(nullable=false)
     * @var Station
     */
    private Station $station;

    /**
     * @Groups({"default"})
     * @ORM\Column(name="lock", type="datetime", nullable=true)
     * @var DateTime| null
     */
    private ?DateTime $lock;

    /**
     * @Groups({"default"})
     * @ORM\Column(name="uploaded_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @var \DateTime
     */
    private DateTime $uploadedAt;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $user = null;

    /**
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\Import\UploadedVaccinationExcelRowEntry", mappedBy="excelFile")
     * @var ArrayCollection
     */
    private  $rows;

    /**
     * @Groups({"default"})
     * @ORM\Column(name="response_hash", type="string", length=32, nullable=true, unique=true)
     * @var string| null
     */
    private ?string $responseHash = null;

    public function __construct()
    {
        $this->rows = new ArrayCollection();
    }

    /**
     * Set fixed
     * @Groups({"default"})
     * @param UploadedVaccinationExcelFileEntry
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setFixed(UploadedVaccinationExcelFileEntry $entry): self
    {
        $this->fixed = $entry;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return UploadedVaccinationExcelFileEntry
     */
    public function getFixed(): UploadedVaccinationExcelFileEntry
    {
        return $this->fixed;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Set status
     *
     * @param VaccinationUploadStatusEnum $status
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setStatus(VaccinationUploadStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return VaccinationUploadStatusEnum
     */
    public function getStatus(): VaccinationUploadStatusEnum
    {
        return $this->status;
    }

    /**
     * Set statusMsg
     *
     * @param string|null $statusMsg
     * @return UploadedVaccinationExcelFileEntry
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
    public function getStatusMsg(): string
    {
        return $this->statusMsg;
    }

    /**
     * Set sourceName
     *
     * @param string $sourceName
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setSourceName(string $sourceName): self
    {
        $this->sourceName = $sourceName;
        return $this;
    }

    /**
     * Get sourceName
     *
     * @return string
     */
    public function getSourceName(): string
    {
        return $this->sourceName;
    }

    /**
     * Set station
     *
     * @param Station $station
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setStation(Station $station): self
    {
        $this->station = $station;
        return $this;
    }

    /**
     * Get station
     *
     * @return Station
     */
    public function getStation(): Station
    {
        return $this->station;
    }

    /**
     * Set lock
     *
     * @param DateTime|null $lock
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setLock(?DateTime $lock): self
    {
        $this->lock = $lock;

        return $this;
    }

    /**
     * Get lock
     *
     * @return DateTime
     */
    public function getLock(): ?DateTime
    {
        return $this->lock;
    }

    /**
     * Set uploadedAt
     * @param DateTime $uploadedAt
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setUploadedAt(DateTime $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;
        return $this;
    }

    /**
     * Get uploadedAt
     *
     * @return DateTime
     */
    public function getUploadedAt(): DateTime
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
    public function addRow(UploadedVaccinationExcelRowEntry $row): self
    {
        if ($this->rows->contains($row)) {
            $this->rows->add($row);
        }

        return $this;
    }

    /**
     * Remove row
     *
     * @param UploadedVaccinationExcelRowEntry $row
     * @return UploadedVaccinationExcelFileEntry
     */
    public function removeRow(UploadedVaccinationExcelRowEntry $row): self
    {
        $this->rows->removeElement($row);
        return $this;
    }

    /**
     * Get rows
     * 
     * @return ArrayCollection
     */
    public function getRows(): Collection
    {
        return $this->rows;
    }

    public function setRows($rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * Set responseHash
     *
     * @param string| null $responseHash
     *
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setResponseHash(?string $responseHash): self
    {
        $this->responseHash = $responseHash;

        return $this;
    }

    /**
     * Get responseHash
     *
     * @return string
     */
    public function getResponseHash(): ?string
    {
        return $this->responseHash;
    }

     /**
     * Get user
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user
     * @param User| null $user
     * @return UploadedVaccinationExcelFileEntry
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
