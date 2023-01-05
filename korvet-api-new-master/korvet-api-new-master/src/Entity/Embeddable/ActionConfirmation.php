<?php


namespace App\Entity\Embeddable;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\IconTrait;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as SWG;

/**
 * Class ActionConfirmation
 * @ORM\Embeddable()
 */
class ActionConfirmation
{
    use IconTrait;

    /**
     * @var string|null
     * @SWG\Property(type="string")
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private  $title;

    /**
     * @var ButtonSettings
     * @SWG\Property(ref=@Model(type=ButtonSettings::class))
     * @Assert\Valid()
     * @Groups({"default"})
     * @ORM\Embedded(class=ButtonSettings::class, columnPrefix="confirm_button_")
     */
    private $confirmButtonSettings;

    /**
     * @var ButtonSettings
     * @SWG\Property(ref=@Model(type=ButtonSettings::class))
     * @Assert\Valid()
     * @Groups({"default"})
     * @ORM\Embedded(class=ButtonSettings::class, columnPrefix="cancel_button_")
     */
    private $cancelButtonSettings;

    /**
     * @var string
     * @SWG\Property(type="string")
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description;

    /**
     * ActionConfirmation constructor.
     */
    public function __construct()
    {
        $this->cancelButtonSettings = new ButtonSettings();
        $this->confirmButtonSettings = new ButtonSettings();
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string|null $title
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param ButtonSettings $confirmButtonSettings
     * @return $this
     */
    public function setConfirmButtonSettings(ButtonSettings $confirmButtonSettings): self
    {
        $this->confirmButtonSettings = $confirmButtonSettings;

        return $this;
    }

    /**
     * @param ButtonSettings $cancelButtonSettings
     * @return $this
     */
    public function setCancelButtonSettings(ButtonSettings $cancelButtonSettings): self
    {
        $this->cancelButtonSettings = $cancelButtonSettings;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return ButtonSettings
     */
    public function getConfirmButtonSettings(): ButtonSettings
    {
        return $this->confirmButtonSettings;
    }

    /**
     * @return ButtonSettings
     */
    public function getCancelButtonSettings(): ButtonSettings
    {
        return $this->cancelButtonSettings;
    }
}
