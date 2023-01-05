<?php

namespace App\Entity\Cash;

use App\Entity\Shift;
use App\Interfaces\CashierUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Packages\DBAL\Types\CashFlowTypeEnum;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Packages\DBAL\Types\CorrectionTypeEnum;
use App\Packages\DBAL\Types\PaymentMethodEnum;
use App\Packages\DBAL\Types\PaymentTypeEnum;
use App\Packages\DBAL\Types\ReceiptDeliveryTypeEnum;
use App\Packages\DBAL\Types\TaxationTypeEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\Reference\CashRegister;
use DateTime;

/**
 * @ORM\Table(schema="cash")
 * @ORM\Entity(repositoryClass="App\Repository\Cash\CashFlowRepository")
 */
class CashFlow
{
    use OrmIdTrait, OrmCreatedAtTrait, OrmDeletedTrait;

    /**
     * @var CashRegister ККМ
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\CashRegister", fetch="EAGER")
     * @ORM\JoinColumn(name="cash_register_id", referencedColumnName="id", nullable=false)
     * @SWG\Property(description="ККМ")
     * @Assert\NotBlank(message="cash_receipt.cash_register.not_blank")
     */
    private CashRegister $cashRegister;

    /**
     * @var CashierUserInterface|null
     */
    private ?CashierUserInterface $creator = null;

    /**
     * @var CashierUserInterface|null
     */
    private ?CashierUserInterface $cashier = null;

    /**
     * @var CashFlowTypeEnum
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\CashFlowTypeEnum", nullable=false)
     * @SWG\Property(type="object", ref=@Model(type=CashFlowTypeEnum::class))
     * @Assert\NotBlank(message="cash_receipt.type.not_blank")
     */
    private CashFlowTypeEnum $type;

    /**
     * @var float| null
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $total = null;

    /**
     * @var FiscalParameters Фискальные параметры при закрытии смены
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Embeddable\FiscalParameters", columnPrefix="fiscal_")
     * @SWG\Property(ref=@Model(type=App\Entity\Embeddable\FiscalParameters::class), description="Фискальные параметры")
     */
    private $fiscal;

    /**
     * @var Shift
     * @Groups({"default"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Shift")
     */
    private Shift $shift;

    /**
     * @return CashFlowTypeEnum
     */
    public function getType(): CashFlowTypeEnum
    {
        return $this->type;
    }

    /**
     * @param CashFlowTypeEnum $type
     * @return CashFlow
     */
    public function setType(CashFlowTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return CashRegister
     */
    public function getCashRegister(): CashRegister
    {
        return $this->cashRegister;
    }

    /**
     * @param CashRegister $cashRegister
     * @return \App\Entity\Cash\CashFlow
     */
    public function setCashRegister(CashRegister $cashRegister): self
    {
        $this->cashRegister = $cashRegister;
        return $this;
    }

    /**
     * @return CashierUserInterface|null
     */
    public function getCreator(): ?CashierUserInterface
    {
        return $this->creator;
    }

    /**
     * @param CashierUserInterface $creator
     */
    public function setCreator(CashierUserInterface $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return CashierUserInterface|null
     */
    public function getCashier(): ?CashierUserInterface
    {
        return $this->cashier;
    }

    /**
     * @param CashierUserInterface $cashier
     */
    public function setCashier(CashierUserInterface $cashier)
    {
        $this->cashier = $cashier;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total ?? 0;
    }

    /**
     * @param $total
     * @return \App\Entity\Cash\CashFlow
     */
    public function setTotal($total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return FiscalParameters|null
     */
    public function getFiscal(): ?FiscalParameters
    {
        return $this->fiscal;
    }

    /**
     * @param FiscalParameters $fiscal
     * @return CashReceipt
     */
    public function setFiscal(?FiscalParameters $fiscal): self
    {
        $this->fiscal = $fiscal;
        return $this;
    }

    /**
     * @return Shift
     */
    public function getShift(): ?Shift
    {
        return $this->shift;
    }

    /**
     * @param Shift $shift
     * @return $this
     */
    public function setShift(?Shift $shift): self
    {
        $this->shift = $shift;

        return $this;
    }
}
