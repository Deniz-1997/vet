<?php

namespace App\Entity\Reference;

use App\Entity\Cash\CashierSchedule;
use App\Interfaces\OrganizationInterface;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmUuidTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmActiveTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(schema="cash")
 * @ORM\Entity()
 */
class CashRegister
{
    use OrmUuidTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait, OrmActiveTrait;

    /**
     * @var OrganizationInterface Организация, на которую зарегистирована ККМ
     * @Groups({"default"})
     * @SWG\Property(description="Организация, на которую зарегистирована ККМ")
     * @Assert\NotBlank(message="cash_register.organization.not_blank")
     */
    private $organization;

    /**
     * @var CashRegisterServer ККМ-сервер, к которому подключена ККМ
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="CashRegisterServer", fetch="EAGER")
     * @ORM\JoinColumn(name="cash_register_server_id", referencedColumnName="id", nullable=false)
     * @SWG\Property(description="ККМ-сервер, к которому подключена ККМ")
     * @Assert\NotBlank(message="cash_register.cash_register_server.not_blank")
     */
    private $cashRegisterServer;

    /**
     * @var CashierSchedule[]
     * @Groups({"expose"})
     * @ORM\OneToMany(targetEntity="App\Entity\Cash\CashierSchedule", mappedBy="cashRegister")
     */
    private $cashierSchedules;

    /**
     * CashRegister constructor.
     */
    public function __construct()
    {
        $this->cashierSchedules = new ArrayCollection();
    }

    /**
     * @return OrganizationInterface
     */
    public function getOrganization(): OrganizationInterface
    {
        return $this->organization;
    }

    /**
     * @param object $organization
     * @return CashRegister
     */
    public function setOrganization($organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return CashRegisterServer
     */
    public function getCashRegisterServer(): CashRegisterServer
    {
        return $this->cashRegisterServer;
    }

    /**
     * @param CashRegisterServer $cashRegisterServer
     * @return CashRegister
     */
    public function setCashRegisterServer(CashRegisterServer $cashRegisterServer): self
    {
        $this->cashRegisterServer = $cashRegisterServer;
        return $this;
    }

    /**
     * @return CashierSchedule[]
     */
    public function getCashierSchedules()
    {
        return $this->cashierSchedules;
    }
}
