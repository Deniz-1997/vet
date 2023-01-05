<?php

namespace App\Entity\Laboratory;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Laboratory\Probe;
use App\Entity\Laboratory\Packing;
use App\Entity\Laboratory\ProbeCorruptReason;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Laboratory\ResearchDocument;
use App\Entity\Laboratory\ProbeSampling;
use OpenApi\Annotations as SWG;
use Doctrine\Common\Collections\ArrayCollection;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Table (schema="laboratory")
 * @ORM\Entity(repositoryClass="App\Repository\Laboratory\ProbeItemRepository")
 */
class ProbeItem
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var Probe Проба
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\Probe")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="laboratory.probe_item.not_blank")
     */
    private \App\Entity\Laboratory\Probe $probe;

    /**
     * @var string| null Маркировка
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(description="Маркировка")
     */
    private ?string $code = null;

    /**
     * @AppAssert\PositiveNumber
     * @var float| null
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Цена")
     */
    private ?float $price = null;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="float", description="Количество")
     * @Assert\Positive(message="document_product.quantity.positive")
     */
    private ?float $quantity = null;

    /**
     * @var Packing Упаковка
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\Packing")
     * @ORM\JoinColumn(nullable=false)
     */
    private \App\Entity\Laboratory\Packing $packing;

    /**
     * @var \DateTime| null Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата")
     */
    private ?\DateTime $date = null;

     /**
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean")
     */
    private ?bool $corrupted = null;

     /**
     * @var \DateTime| null Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата")
     */
    private ?\DateTime $corruptedDate = null;

      /**
     * @var ProbeCorruptReason| null причина брака
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\ProbeCorruptReason")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Laboratory\ProbeCorruptReason $corruptReason = null;

     /**
     * @var ProbeSampling| null Отбор проб
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Laboratory\ProbeSampling", inversedBy="probeItem")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?\App\Entity\Laboratory\ProbeSampling $probeSampling = null;

      /**
     * @var ResearchDocument[]
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\Laboratory\ResearchDocument", mappedBy="probeItem",  cascade={"persist"})
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=ResearchDocument::class)))
     */
    private $researchDocuments;

     /**
     * @var float| null
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $amount = null;

    public function __construct()
    {
        $this->researchDocuments = new ArrayCollection();
    }

    /**
     * @return Probe
     */
    public function getProbe(): Probe
    {
        return $this->probe;
    }

    /**
     * @param \App\Entity\Laboratory\Probe|null $probe
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function setProbe(?Probe $probe): self
    {
        $this->probe = $probe;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return ProbeSampling|null
     */
    public function getProbeSampling(): ?ProbeSampling
    {
        return $this->probeSampling;
    }

    /**
     * @param ProbeSampling|null $probeSampling
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function setProbeSampling(?ProbeSampling $probeSampling): self
    {
        $this->probeSampling = $probeSampling;
        return $this;
    }

    /**
     * @return Packing
     */
    public function getPacking(): Packing
    {
        return $this->packing;
    }

    /**
     * @param Packing $packing
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function setPacking(Packing $packing): self
    {
        $this->packing = $packing;
        return $this;
    }

     /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return ProbeItem
     */
    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getCorrupted(): ?bool
    {
        return $this->corrupted;
    }

    public function setCorrupted(?bool $corrupted): self
    {
        $this->corrupted = $corrupted;

        return $this;
    }

     /**
     * @return \DateTime|null
     */
    public function getCorruptedDate(): ?\DateTime
    {
        return $this->corruptedDate;
    }

    /**
     * @param \DateTime|null $corruptedDate
     * @return ProbeItem
     */
    public function setCorruptedDate(?\DateTime $corruptedDate): self
    {
        $this->corruptedDate = $corruptedDate;
        return $this;
    }

     /**
     * @return ProbeCorruptReason|null
     */
    public function getCorruptReason(): ?ProbeCorruptReason
    {
        return $this->corruptReason;
    }

    /**
     * @param ProbeCorruptReason|null $corruptReason
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function setCorruptReason(?ProbeCorruptReason $corruptReason): self
    {
        $this->corruptReason = $corruptReason;
        return $this;
    }

      /**
     * @return ResearchDocument[]|object
     */
    public function getResearchDocuments()
    {
        return $this->researchDocuments;
    }

    /**
     * @param ResearchDocument $researchDocument
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function addResearchDocument(ResearchDocument $researchDocument)
    {
        $researchDocument->setProbeItem($this);
        if (!$this->getResearchDocuments()->contains($researchDocument)) {
            $this->getResearchDocuments()->add($researchDocument);
        }
        return $this;
    }

    /**
     * @param ResearchDocument $researchDocument
     * @return \App\Entity\Laboratory\ProbeItem
     */
    public function removeResearchDocument(ResearchDocument $researchDocument)
    {
        if ($this->getResearchDocuments()->contains($researchDocument)) {
            $this->getResearchDocuments()->removeElement($researchDocument);
        }
        return $this;
    }

      /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

}
