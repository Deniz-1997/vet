<?php

namespace App\Entity\Leaving;

use App\Entity\Leaving\Leaving;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Entity\Pet\Weight;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Appointment\Appointment;

/**
 * @ORM\Table("leaving_weight", schema="leaving")
 * @ORM\Entity(repositoryClass="App\Repository\Leaving\LeavingWeightRepository")
 */
class LeavingWeight
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var Weight weight
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Pet\Weight", inversedBy="appointmentWeight", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="weight_id", referencedColumnName="id")
     * @SWG\Property(description="Вес")
     */
    private $weight;


    /**
     * @var Leaving| null leaving
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Leaving\Leaving")
     * @ORM\JoinColumn(nullable=true, name="leaving_id", referencedColumnName="id")
     * @SWG\Property(description="Выезд")
     */
    private $leaving;

     /**
     * @param Weight $weight
     * @return LeavingWeight
     */
    public function setWeight(Weight $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
    /**
     * @return Weight
     */
    public function getWeight()
    {
        return $this->weight;
    }



    /**
     * @param Leaving $leaving
     * @return $this
     */
    public function setLeaving(Leaving $leaving): self
    {
        $this->leaving = $leaving;

        return $this;
    }

    /**
     * @return Leaving
     */
    public function getLeaving()
    {
        return $this->leaving;
    }
}
