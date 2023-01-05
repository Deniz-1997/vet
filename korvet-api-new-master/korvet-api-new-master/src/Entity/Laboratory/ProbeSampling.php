<?php

namespace App\Entity\Laboratory;

use App\Entity\Owner;
use App\Entity\Pet\Pet;
use App\Entity\Laboratory\Laboratory;
use App\Entity\Laboratory\ProbeItem;
use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Appointment\Appointment;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\Cash\CashReceipt;
use App\Packages\DBAL\Types\PaymentTypeEnum;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Laboratory\ProbeSamplingRepository")
 * @ORM\Table(schema="laboratory")
 */
class ProbeSampling
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var \DateTime Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime")
     * @SWG\Property(description="Дата")
     */
    private \DateTime $date;

    /**
     * @var Pet|null Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", fetch="EAGER")
     * @SWG\Property(description="Животное")
     */
    private ?Pet $pet = null;

    /**
     * @var Owner|null Владелец
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner")
     */
    private ?Owner $owner = null;

    /**
     * @var User
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(description="Специалист")
     */
    private User $user;

    /**
     * @var string Комментарий
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(description="Комментарий")
     */
    private string $comment;

    /**
     * @var Appointment Прием
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment\Appointment")
     */
    private Appointment $appointment;

     /**
     * @var ProbeItem[]
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\Laboratory\ProbeItem", mappedBy="probeSampling",  cascade={"persist"})
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=ProbeItem::class)))
     */
    private $probeItems;

     /**
     * @var CashReceipt Чек ККМ
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Cash\CashReceipt")
     * @SWG\Property(description="Чек ККМ")
     */
    private $cashReceipt;
    
     /**
     * @var PaymentTypeEnum|null
     * @Groups({"default"})
     * @SWG\Property(ref=@Model(type=App\Packages\DBAL\Types\PaymentTypeEnum::class), example="CASH")
     * @ORM\Column(type="App\Packages\DBAL\Types\PaymentTypeEnum", nullable=true)
     */
    private ?PaymentTypeEnum $paymentType = null;

    public function __construct()
    {
        $this->probeItems = new ArrayCollection();
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
     * @return ProbeSampling
     */
    public function setDate(\DateTime $date): ProbeSampling
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return Pet|null
     */
    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    /**
     * @param \App\Entity\Pet\Pet|null $pet
     * @return \App\Entity\Laboratory\ProbeSampling
     */
    public function setPet(?Pet $pet): self
    {
        $this->pet = $pet;
        return $this;
    }

    /**
     * @return Owner|null
     */
    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    /**
     * @param \App\Entity\Owner|null $owner
     * @return \App\Entity\Laboratory\ProbeSampling
     */
    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return \App\Entity\Laboratory\ProbeSampling
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return Appointment|null
     */
    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    /**
     * @param Appointment|null $appointment
     * @return \App\Entity\Laboratory\ProbeSampling
     */
    public function setAppointment(?Appointment $appointment): self
    {
        $this->appointment = $appointment;
        return $this;
    }

     /**
     * @return ProbeItem[]|object
     */
    public function getProbeItems()
    {
        return $this->probeItems;
    }

    /**
     * @param \App\Entity\Laboratory\ProbeItem $probeItem
     * @return \App\Entity\Laboratory\ProbeSampling
     */
    public function addProbeItem(ProbeItem $probeItem)
    {
        $probeItem->setProbeSampling($this);
        if (!$this->getProbeItems()->contains($probeItem)) {
            $this->getProbeItems()->add($probeItem);
        }
        return $this;
    }

    /**
     * @param ProbeItem $probeItem
     * @return \App\Entity\Laboratory\ProbeSampling
     */
    public function removeProbeItem(ProbeItem $probeItem)
    {
        if ($this->getProbeItems()->contains($probeItem)) {
            $this->getProbeItems()->removeElement($probeItem);
        }
        return $this;
    }

      /**
     * @return PaymentTypeEnum|null
     */
    public function getPaymentType(): ?PaymentTypeEnum
    {
        return $this->paymentType;
    }

    /**
     * @param PaymentTypeEnum|null $paymentType
     * @return \App\Entity\Laboratory\ProbeSampling
     */
    public function setPaymentType(?PaymentTypeEnum $paymentType): self
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @return null|CashReceipt
     */
    public function getCashReceipt(): ?CashReceipt
    {
        return $this->cashReceipt;
    }

    /**
     * @param CashReceipt|null $cashReceipt
     */
    public function setCashReceipt(?CashReceipt $cashReceipt): void
    {
        $this->cashReceipt = $cashReceipt;
    }

     /**
     * @return $this
     */
    public function removeCashReceipt(): self
    {
        if ($this->cashReceipt) {
            $this->cashReceipt->setDeleted(true);
            $this->cashReceipt = null;
        }
        return $this;
    }
}
