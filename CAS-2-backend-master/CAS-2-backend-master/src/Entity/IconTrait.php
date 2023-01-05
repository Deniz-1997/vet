<?php


namespace App\Entity;

use App\Entity\Reference\Icon;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

trait IconTrait
{
    /**
     * @Assert\Valid()
     * @var Icon|null
     * @SWG\Property(ref=@Model(type=Icon::class))
     * @Groups({"default"})
     */
    private $icon;

    /**
     * @var integer|null
     * @Groups({"expose"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $iconId;

    /**
     * @return Icon|null
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param Icon|null $icon
     * @return $this
     */
    public function setIcon($icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIconId(): ?int
    {
        return $this->iconId;
    }

    /**
     * @param int|null $iconId
     * @return $this
     */
    public function setIconId(?int $iconId): self
    {
        $this->iconId = $iconId;

        return $this;
    }
}
