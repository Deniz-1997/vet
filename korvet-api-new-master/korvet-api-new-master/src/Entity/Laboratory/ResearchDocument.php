<?php

namespace App\Entity\Laboratory;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Laboratory\ResearchReason;
use App\Entity\Laboratory\Research;
use App\Entity\Laboratory\ProbeSampling;
use App\Entity\Laboratory\ResearchEquipment;
use App\Packages\DBAL\Types\ResearchStateEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\User\User;
use Ramsey\Uuid\Uuid;
use App\Traits\ORMTraits\Complex\ORMDocumentTrait;
use App\Entity\Reference\Stock;
use App\Enum\DocumentOperationTypeEnum;
use App\Model\BaseDocument;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentResearchInterface;
use App\Entity\File;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Table (schema="laboratory")
 * @ORM\Entity(repositoryClass="App\Repository\Laboratory\ResearchDocumentRepository")
 */
class ResearchDocument extends BaseDocument implements DocumentResearchInterface
{
    use OrmDeletedTrait, ORMDocumentTrait;

    /**
     * @var ResearchReason| null Причина исследования
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\ResearchReason")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Laboratory\ResearchReason $researchReason = null;

    /**
     * @var ResearchPriority| null Приоритет исследования
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\ResearchPriority")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?ResearchPriority $researchPriority = null;

    /**
     * @AppAssert\PositiveNumber
     * @var float| null
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Цена")
     */
    private ?float $price = null;

    /**
     * @var ResearchStateEnum Статус
     * @Groups({"default"})
     * @Assert\NotNull()
     * @SWG\Property(ref=@Model(type=App\Packages\DBAL\Types\ResearchStateEnum::class))
     * @ORM\Column(type="App\Packages\DBAL\Types\ResearchStateEnum", nullable=false, options={"default": ResearchStateEnum::CREATE})
     * @Assert\NotBlank(message="laboratory.probe_sampling_state.not_blank")
     */
    private $status;

    /**
     * @var ProbeItem|null проба
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\ProbeItem", inversedBy="researchDocument")
     * @Assert\NotNull()
     */
    private ?ProbeItem $probeItem = null;

    /**
     * @var Research Исследование
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\Research")
     * @ORM\JoinColumn(nullable=false)
     */
    private \App\Entity\Laboratory\Research $research;

    /**
     * @var \DateTime Дата завершения исследования
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата завершения исследования")
     */
    private \DateTime $dateEnd;

    /**
     * @var ResearchEquipment| null Исследование
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\ResearchEquipment")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Laboratory\ResearchEquipment $researchEquipment = null;

    /**
     * @var string| null Заключение
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(description="Заключение")
     */
    private ?string $result = null;

      /**
     * @var Laboratory Лаборатория
     * @Groups({"default"})
     * @SWG\Property(description="Лаборатория")
     * @ORM\ManyToOne(targetEntity="App\Entity\Laboratory\Laboratory")
     */
    private Laboratory $laboratory;

    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
        $this->id = Uuid::uuid4();
        $this->status = clone ResearchStateEnum::getItem(ResearchStateEnum::CREATE);
    }

    /**
     * @return ResearchReason|null
     */
    public function getResearchReason()
    {
        return $this->researchReason;
    }

    /**
     * @param ResearchReason|null $researchReason
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setResearchReason(?ResearchReason $researchReason): self
    {
        $this->researchReason = $researchReason;
        return $this;
    }

    /**
     * @return ResearchPriority|null
     */
    public function getResearchPriority()
    {
        return $this->researchPriority;
    }

    /**
     * @param ResearchPriority|null $researchPriority
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setResearchPriority(?ResearchPriority $researchPriority): self
    {
        $this->researchPriority = $researchPriority;
        return $this;
    }

    /**
     * @return Research
     */
    public function getResearch()
    {
        return $this->research;
    }

    /**
     * @param Research|null $research
     */
    public function setResearch(Research $research): self
    {
        $this->research = $research;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return ResearchStateEnum
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param ResearchStateEnum $status
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setStatus(ResearchStateEnum $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return ProbeItem|null
     */
    public function getProbeItem(): ?ProbeItem
    {
        return $this->probeItem;
    }

    /**
     * @param ProbeItem $probeItem
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setProbeItem(ProbeItem $probeItem): self
    {
        $this->probeItem = $probeItem;
        return $this;
    }
   
    /**
     * @return \DateTime|null
     */
    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime|null $dateEnd
     * @return self
     */
    public function setDateEnd(?\DateTime $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * @return ResearchEquipment|null
     */
    public function getResearchEquipment(): ?ResearchEquipment
    {
        return $this->researchEquipment;
    }

    /**
     * @param ResearchEquipment|null $researchEquipment
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setResearchEquipment(?ResearchEquipment $researchEquipment): self
    {
        $this->researchEquipment = $researchEquipment;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param string $result
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setResult(string $result): self
    {
        $this->result = $result;
        return $this;
    }

     /**
     * @return Stock|null
     */
    public function getStock(): ?Stock
    {
        return $this->getLaboratory() === null ? null : $this->getLaboratory()->getStock();
    }

    /**
     * @param Stock $stock
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setStock(Stock $stock): self
    {
        $this->getLaboratory()->setStock($stock);
        return $this;
    }

     /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->id->toString();
    }

     /**
     * @return DocumentOperationTypeEnum|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getOperationType() : ?DocumentOperationTypeEnum
    {
        return DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::LABORATORY);
    }

    /**
     * @return DocumentInterface
     */
    public function clearErrors(): DocumentInterface
    {
        $this->setErrors(null);
        return $this;
    }

     /**
     * @return Laboratory|null
     */
    public function getLaboratory(): ?Laboratory
    {
        return $this->laboratory;
    }

    /**
     * @param Laboratory|null $laboratory
     * @return \App\Entity\Laboratory\ResearchDocument
     */
    public function setLaboratory(?Laboratory $laboratory): self
    {
        $this->laboratory = $laboratory;

        return $this;
    }
}
