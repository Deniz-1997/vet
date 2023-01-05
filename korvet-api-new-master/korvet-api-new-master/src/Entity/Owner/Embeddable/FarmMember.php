<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 29/11/2018
 * Time: 20:36
 */

namespace App\Entity\Owner\Embeddable;


use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 */
class FarmMember
{
    /**
     * @var Person|null Участник
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Person", columnPrefix=false)
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Person::class), description="Участник")
     */
    private $person;

    /**
     * @var float|null Доля
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(type="number", format="float", description="Доля")
     */
    private $share;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return FarmMember
     */
    public function setPerson(?Person $person): FarmMember
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getShare(): ?float
    {
        return $this->share;
    }

    /**
     * @param float|null $share
     * @return FarmMember
     */
    public function setShare(?float $share): FarmMember
    {
        $this->share = $share;
        return $this;
    }

    
}
