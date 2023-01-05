<?php

namespace App\Entity\Reference;

use App\Repository\Reference\DisinfectantRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Reference\MeasurementUnits;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DisinfectantRepository::class)
 * @ORM\Table ("reference_disinfectant", schema="reference")
 */
class Disinfectant
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait, OrmCreatedAtTrait;

    /**
     * @var MeasurementUnits| null Еденицы измерения
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\MeasurementUnits")
     * @ORM\JoinColumn (nullable=true)
     */
    private ?MeasurementUnits $measurementUnits = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, length=50)
     */
    private ?string $kind = null;

    /**
     * @var int
     * @Groups({"default"})
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $mult;

    /**
     * Get the value of kind
     *
     * @return  string
     */ 
    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * Set the value of kind
     *
     * @param  string  $kind
     *
     * @return  self
     */ 
    public function setKind(string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get the value of mult
     *
     * @return  int
     */ 
    public function getMult(): int
    {
        return $this->mult;
    }

    /**
     * Set the value of mult
     *
     * @param  int  $mult
     *
     * @return  self
     */ 
    public function setMult(int $mult): self
    {
        $this->mult = $mult;

        return $this;
    }

    /**
     * Get null Еденицы измерения
     *
     * @return  MeasurementUnits|
     */ 
    public function getMeasurementUnits(): ?MeasurementUnits
    {
        return $this->measurementUnits;
    }

    /**
     * Set null Еденицы измерения
     *
     * @param MeasurementUnits| null $measurementUnits null Еденицы измерения
     *
     * @return  self
     */
    public function setMeasurementUnits(?MeasurementUnits $measurementUnits): self
    {
        $this->measurementUnits = $measurementUnits;

        return $this;
    }
}
