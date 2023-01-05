<?php

namespace App\Entity;


use App\Entity\Reference\BusinesEntity;
use App\Entity\Reference\Station;
use App\Entity\Reference\SupervisedObjects;
use App\Packages\DBAL\Types\ReportStatusEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use App\Traits\ORMTraits\OrmIdTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Packages\Annotation\History;


/**
 * @ORM\Table(schema="reports")
 * @ORM\Entity(repositoryClass="App\Repository\ReportsDataRepository")
 * @Gedmo\Loggable(logEntryClass="App\Entity\HistoryEntity")
 * @History(alias="reportsData", relationField={"statusId"})
 */
class ReportsData
{
    use OrmIdTrait;

    /**
     * @var Reports
     * @Groups({"default"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned()
     * @ORM\ManyToOne(targetEntity="App\Entity\Reports")
     */
    private $reports;

    /**
     * @var Station
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Station")
     * @Gedmo\Versioned()
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id", nullable=false)
     */
    private Station $station;
    /**
     * @var BusinesEntity| null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\BusinesEntity")
     * @Gedmo\Versioned()
     * @ORM\JoinColumn(name="businessEntity_id", nullable=true)
     */
    private  ?BusinesEntity $businessEntity;
    /**
     * @var SupervisedObjects | null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\SupervisedObjects")
     * @Gedmo\Versioned()
     * @ORM\JoinColumn(name="supervisedObjects_id", nullable=true)
     */
    private  ?SupervisedObjects $supervisedObjects;

    /**
     * @var ReportStatusEnum
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="App\Packages\DBAL\Types\ReportStatusEnum", nullable=false, options={"default": ReportStatusEnum::NEW})
     */
    private ReportStatusEnum $statusId;

    /**
     * @var integer|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $year;

    /**
     * @var integer|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $quarter;

    /**
     * @var integer|null
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $month;

    /**
     * @Groups({"default"})
     * @Gedmo\Versioned()
     * @ORM\Column(type="json")
     */
    private array $data = [];

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getReports(): Reports
    {
        return $this->reports;
    }

    public function setReports(Reports $reports): self
    {
        $this->reports = $reports;
        return $this;

    }

    public function getStation(): Station
    {
        return $this->station;
    }

    public function setStation(Station $station):self
    {
        $this->station = $station;
        return $this;
    }
    public function getBusinessEntity(): ?BusinesEntity
    {
        return $this->businessEntity;
    }

    public function setBusinessEntity(?BusinesEntity $businesEntity):self
    {
        $this->businessEntity = $businesEntity;
        return $this;
    }
    public function getSupervisedObjects(): ?SupervisedObjects
    {
        return $this->supervisedObjects;
    }

    public function setSupervisedObjects(?SupervisedObjects $supervisedObjects):self
    {
        $this->supervisedObjects = $supervisedObjects;
        return $this;
    }

    public function getStatusId(): ReportStatusEnum
    {
        return $this->statusId;
    }

    public function setStatusId(ReportStatusEnum $statusId): self
    {
        $this->statusId = $statusId;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getQuarter(): int
    {
        return $this->quarter;
    }

    public function setQuarter(int $quarter): self
    {
        $this->quarter = $quarter;
        return $this;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

}
