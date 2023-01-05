<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 29/11/2018
 * Time: 00:04
 */

namespace App\Entity\Owner\Embeddable;


use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 */
class Entrepreneur
{
    /**
     * @var string|null ЕГРИП
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="ЕГРИП")
     */
    private $egrip;

    /**
     * @var string|null ОГРНИП
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="ОГРНИП")
     */
    private $ogrnip;

    /**
     * @return null|string
     */
    public function getEgrip(): ?string
    {
        return $this->egrip;
    }

    /**
     * @param null|string $egrip
     * @return Entrepreneur
     */
    public function setEgrip(?string $egrip): Entrepreneur
    {
        $this->egrip = $egrip;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getOgrnip(): ?string
    {
        return $this->ogrnip;
    }

    /**
     * @param null|string $ogrnip
     * @return Entrepreneur
     */
    public function setOgrnip(?string $ogrnip): Entrepreneur
    {
        $this->ogrnip = $ogrnip;
        return $this;
    }


}
