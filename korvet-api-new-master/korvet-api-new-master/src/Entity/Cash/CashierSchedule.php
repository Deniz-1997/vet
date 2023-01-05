<?php

namespace App\Entity\Cash;

use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Packages\Annotation\ExternalEntityReference;
use App\Entity\Reference\CashRegister;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cash\CashierScheduleRepository")
 */
class CashierSchedule
{
    use OrmIdTrait, OrmCreatedAtTrait, OrmDeletedTrait;

    /**
     * @var \DateTime Начало периода
     * @Groups({"default"})
     * @SWG\Property(description="Начало периода")
     * @Assert\NotBlank(message="cashier_schedule.date_from.not_blank")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private \DateTime $dateFrom;

    /**
     * @var \DateTime Конец периода
     * @Groups({"default"})
     * @SWG\Property(description="Конец периода")
     * @Assert\NotBlank(message="cashier_schedule.date_to.not_blank")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private \DateTime $dateTo;

    /**
     * @var object Кассир
     * @Groups({"default"})
     * @Assert\NotBlank(message="cashier_schedule.cashier.not_blank")
     * @SWG\Property(description="Кассир")
     */
    private object $cashier;

    /**
     * @var CashRegister ККМ
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\CashRegister", inversedBy="cashierSchedules", fetch="EAGER")
     * @ORM\JoinColumn(name="cash_register_id", referencedColumnName="id", nullable=false)
     * @SWG\Property(description="ККМ")
     * @Assert\NotBlank(message="cashier_schedule.cash_register.not_blank")
     */
    private CashRegister $cashRegister;

    /**
     * @return \DateTime
     */
    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime $dateFrom
     * @return CashierSchedule
     */
    public function setDateFrom(\DateTime $dateFrom): CashierSchedule
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    /**
     * @param \DateTime $dateTo
     * @return CashierSchedule
     */
    public function setDateTo(\DateTime $dateTo): CashierSchedule
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return object
     */
    public function getCashier(): ?object
    {
        return $this->cashier;
    }

    /**
     * @param object $cashier
     * @return CashierSchedule
     */
    public function setCashier(object $cashier): CashierSchedule
    {
        $this->cashier = $cashier;
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
     * @return CashierSchedule
     */
    public function setCashRegister(CashRegister $cashRegister): CashierSchedule
    {
        $this->cashRegister = $cashRegister;
        return $this;
    }
}
