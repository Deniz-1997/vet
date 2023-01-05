<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use App\Enum\FtpHistoryTypeEnum;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FtpHistoryRepository")
 */
class FtpHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(description="Имя файла")
     */
    private $fileName;

    /**
     * @var FtpHistoryTypeEnum
     * @Groups({"default"})
     * @ORM\Column(type=FtpHistoryTypeEnum::class, nullable=true) // указываем класс перечисления
     * @SWG\Property(ref=@Model(type=FtpHistoryTypeEnum::class), description="Тип операции")
     */
    private $operationType;

    /**
     * @var \DateTime| null
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата")
     */
    private  $date;

    /**
     * @var boolean| null
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private  $imported ;

    /**
     * @var array| null
     * @Groups({"default"})
     * @ORM\Column(type="json", options={"jsonb":true}, nullable=true)
     * @SWG\Property(description="Дополнительная информация")
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ImportExportFile")
     */
    private  $uploadImportExportFile;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return FtpHistoryTypeEnum
     */
    public function getOperationType(): FtpHistoryTypeEnum
    {
        return $this->operationType;
    }

    /**
     * @param FtpHistoryTypeEnum|null $operationType
     * @return FtpHistory
     */
    public function setOperationType(?FtpHistoryTypeEnum $operationType): self
    {
        $this->operationType = $operationType;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return FtpHistory
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * @param boolean $imported
     * @return FtpHistory
     */
    public function setImported(bool $imported): self
    {
        $this->imported = $imported;
        return $this;
    }

    /**
     * @return array
     */
    public function getReport(): ?array
    {
        return $this->report === null ? []: $this->report;
    }

    /**
     * @param array $report
     * @return FtpHistory
     */
    public function setReport(array $report): self
    {
        $this->report = $report;
        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return ImportExportFile|null
     */
    public function getUploadImportExportFile(): ?ImportExportFile
    {
        return $this->uploadImportExportFile;
    }

    /**
     * @param ImportExportFile|null $uploadImportExportFile
     * @return $this
     */
    public function setUploadImportExportFile(?ImportExportFile $uploadImportExportFile): self
    {
        $this->uploadImportExportFile = $uploadImportExportFile;

        return $this;
    }
}
