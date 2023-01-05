<?php

namespace App\Entity\Reference;

use App\Interfaces\StockInterface;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\StockRepository")
 * @ORM\Table("reference_stock", schema="reference")
 */
class Stock implements StockInterface
{
    use OrmReferenceTrait, OrmExternalIdTrait , OrmSortTrait;

    /**
     * @var bool Показывать на странице приема
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default": 1})
     * @SWG\Property(type="boolean", description="Показывать на странице приема")
     */
    private $showInAppointment = false;

    /**
     * @var Unit Возврщает клинику к которой принадлежит
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Unit", fetch="EAGER")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id", nullable=true)
     * @SWG\Property(type="Клиника")
     */
    private $unit;

    /**
     * @return bool
     */
    public function isShowInAppointment(): bool
    {
        return $this->showInAppointment;
    }

    /**
     * @param bool $showInAppointment
     * @return Stock
     */
    public function setShowInAppointment(bool $showInAppointment): Stock
    {
        $this->showInAppointment = $showInAppointment;
        return $this;
    }

    /**
     * @param null|string $externalId
     * @return $this
     */
    public function setExternalId(?string $externalId): self
    {
        if ($externalId !== null && trim($externalId) === '') {
            throw new \InvalidArgumentException('stock.wrong_external_id');
        }

        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return Unit|null
     */
    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     * @return Stock
     */
    public function setUnit($unit): Stock
    {
        $this->unit = $unit;
        return $this;
    }
}
