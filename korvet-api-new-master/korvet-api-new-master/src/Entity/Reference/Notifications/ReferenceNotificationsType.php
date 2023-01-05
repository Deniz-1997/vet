<?php

namespace App\Entity\Reference\Notifications;

use App\Repository\Reference\Notifications\ReferenceNotificationsTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass=ReferenceNotificationsTypeRepository::class)
 * @ORM\Table(schema="reference")
 */
class ReferenceNotificationsType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Groups("default")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @Groups("default")
     * @ORM\Column(type="text")
     */
    private ?string $template;

    /**
     * @var boolean
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     * @SWG\Property(description="Обязателен", type="boolean", default=false)
     */
    private ?bool $required;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }
}
