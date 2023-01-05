<?php

namespace App\Entity\Leaving;

use App\Entity\Leaving\Leaving;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Entity\Pet\Temperature;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Appointment\Appointment;

/**
 * @ORM\Table(schema="leaving")
 * @ORM\Entity(repositoryClass="App\Repository\Leaving\LeavingTemperatureRepository")
 */
class LeavingTemperature
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var Temperature temperature
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Pet\Temperature", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="temperature_id", referencedColumnName="id")
     * @SWG\Property(description="Температура")
     */
    private $temperature;



    /**
     * @var Leaving| null leaving
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Leaving\Leaving")
     * @ORM\JoinColumn(nullable=true, name="leaving_id", referencedColumnName="id")
     * @SWG\Property(description="Выезд")
     */
    private $leaving;

     /**
     * @param Temperature $temperature
     * @return LeavingTemperature
     */
    public function setTemperature(Temperature $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }
    /**
     * @return Temperature
     */
    public function getTemperature()
    {
        return $this->temperature;
    }


    /**
     * @param Leaving| null $leaving
     * @return $this
     */
    public function setLeaving(?Leaving $leaving): self
    {
        $this->leaving = $leaving;

        return $this;
    }

    /**
     * @return ?Leaving
     */
    public function getLeaving(): ?Leaving
    {
        return $this->leaving;
    }
}
