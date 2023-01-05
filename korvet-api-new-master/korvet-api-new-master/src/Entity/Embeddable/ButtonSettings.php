<?php


namespace App\Entity\Embeddable;

use App\Entity\IconTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * Class ButtonSettings
 * @ORM\Embeddable()
 */
class ButtonSettings
{
    use IconTrait;

    /**
     * @var string|null
     * @Groups({"default"})
     * @SWG\Property(type="string")
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    /**
     * @var string|null
     * @Groups({"default"})
     * @SWG\Property(type="string")
     * @ORM\Column(type="string", nullable=true)
     */
    private $backgroundColor;

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return $this
     */
    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string|null $backgroundColor
     * @return $this
     */
    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }
}
