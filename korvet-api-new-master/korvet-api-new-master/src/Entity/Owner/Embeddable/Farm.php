<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 29/11/2018
 * Time: 00:04
 */

namespace App\Entity\Owner\Embeddable;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 */
class Farm
{
    /**
     * @var string|null ЕГРИП
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="ЕГРИП")
     */
    private $egrip;

    /**
     * @var FarmMember|null Глава КФХ
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\FarmMember", columnPrefix="head_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\FarmMember::class), description="Глава КФХ")
     */
    private $head;

    /**
     * @return null|string
     */
    public function getEgrip(): ?string
    {
        return $this->egrip;
    }

    /**
     * @param null|string $egrip
     * @return Farm
     */
    public function setEgrip(?string $egrip): Farm
    {
        $this->egrip = $egrip;
        return $this;
    }

    /**
     * @return FarmMember|null
     */
    public function getHead(): ?FarmMember
    {
        return $this->head;
    }

    /**
     * @param FarmMember|null $head
     * @return Farm
     */
    public function setHead(?FarmMember $head): Farm
    {
        $this->head = $head;
        return $this;
    }
}
