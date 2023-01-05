<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 29/11/2018
 * Time: 16:27
 */

namespace App\Entity\Owner\Embeddable;


use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 */
class Address
{
    /**
     * @var string|null Полный адрес строкой
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Полный адрес строкой")
     */
    private $full;

    /**
     * @var string|null Географические координаты
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Географические координаты (формат 55.755831, 37.617673)")
     */
    private $coordinates;

    /**
     * @return null|string
     */
    public function getFull(): ?string
    {
        return $this->full;
    }

    /**
     * @param null|string $full
     * @return Address
     */
    public function setFull(?string $full): Address
    {
        $this->full = $full;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    /**
     * @param null|string $coordinates
     * @return Address
     */
    public function setCoordinates(?string $coordinates): Address
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    
}
