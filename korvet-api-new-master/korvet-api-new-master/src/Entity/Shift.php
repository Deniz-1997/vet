<?php

namespace App\Entity;

use App\Interfaces\CashierUserInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Packages\DBAL\Types\ShiftStateEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\Reference\CashRegister;

/**
 * Class Shift
 * @ORM\Entity(repositoryClass="App\Repository\ShiftRepository")
 */
class Shift
{
    use OrmIdTrait, OrmDeletedTrait, OrmCreatedAtTrait;

    /**
     * @var CashRegister ККМ
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\CashRegister", fetch="EAGER")
     * @ORM\JoinColumn(name="cash_register_id", referencedColumnName="id", nullable=false)
     * @SWG\Property(description="ККМ")
     * @Assert\NotBlank(message="shift.cash_register.not_blank")
     */
    protected  $cashRegister;

    /**
     * @var CashierUserInterface|null
     * @Groups({"list", "detail"})
     */
    protected  $creator;

    /**
     * @var CashierUserInterface|null
     */
    protected $cashier;

    /**
     * @var ShiftStateEnum
     * @Groups({"default"})
     * @SWG\Property(type="object", ref=@Model(type=ShiftStateEnum::class))
     */
    protected $state;

    /**
     * @var FiscalParameters|null Фискальные параметры при открытии смены
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Embeddable\FiscalParameters", columnPrefix="fiscal_open_")
     * @SWG\Property(ref=@Model(type=App\Entity\Embeddable\FiscalParameters::class), description="Фискальные параметры при открытии смены")
     */
    protected $fiscalOpen;

    /**
     * @var FiscalParameters Фискальные параметры при закрытии смены
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Embeddable\FiscalParameters", columnPrefix="fiscal_close_")
     * @SWG\Property(ref=@Model(type=App\Entity\Embeddable\FiscalParameters::class), description="Фискальные параметры при закрытии смены")
     */
    protected $fiscalClose;

    /**
     * @return CashRegister
     */
    public function getCashRegister(): CashRegister
    {
        return $this->cashRegister;
    }

    /**
     * @param CashRegister $cashRegister
     * @return Shift
     */
    public function setCashRegister(CashRegister $cashRegister): Shift
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
     * @param CashierUserInterface $creator|null
     * @return Shift
     */
    public function setCreator(?CashierUserInterface $creator): Shift
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return CashierUserInterface|null
     */
    public function getCashier(): ?CashierUserInterface
    {
        return $this->cashier;
    }

    /**
     * @param CashierUserInterface|null $cashier
     * @return Shift
     */
    public function setCashier(?CashierUserInterface $cashier): Shift
    {
        $this->cashier = $cashier;
        return $this;
    }

    /**
     * @return ShiftStateEnum
     * @throws \Doctrine\DBAL\Exception
     */
    public function getState(): ShiftStateEnum
    {
        $isOpened = ($this->fiscalOpen && $this->fiscalOpen->getState() && $this->fiscalOpen->getState()->code === FiscalReceiptStateEnum::DONE);
        $isClosed = ($this->fiscalClose && $this->fiscalClose->getState() && $this->fiscalClose->getState()->code === FiscalReceiptStateEnum::DONE);

        if ($isOpened) {
            if ($isClosed) {
                return ShiftStateEnum::getItem(ShiftStateEnum::CLOSE);
            } else {
                return ShiftStateEnum::getItem(ShiftStateEnum::OPEN);
            }
        } else {
            return ShiftStateEnum::getItem(ShiftStateEnum::NEW);
        }
    }

    /**
     * @return FiscalParameters|null
     */
    public function getFiscalOpen(): ?FiscalParameters
    {
        return $this->fiscalOpen;
    }

    /**
     * @param FiscalParameters $fiscalOpen
     * @return Shift
     */
    public function setFiscalOpen(FiscalParameters $fiscalOpen): Shift
    {
        $this->fiscalOpen = $fiscalOpen;
        return $this;
    }

    /**
     * @return FiscalParameters|null
     */
    public function getFiscalClose(): ?FiscalParameters
    {
        return $this->fiscalClose;
    }

    /**
     * @param FiscalParameters $fiscalClose
     * @return Shift
     */
    public function setFiscalClose(FiscalParameters $fiscalClose): Shift
    {
        $this->fiscalClose = $fiscalClose;
        return $this;
    }
}
