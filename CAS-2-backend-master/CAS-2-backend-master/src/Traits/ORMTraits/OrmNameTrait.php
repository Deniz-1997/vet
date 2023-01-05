<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

trait OrmNameTrait
{
    /**
     * @var string Наименование
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default",		 
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Наименование", type="string")
     * @Assert\NotBlank(message="name.not_blank")
     */
    private string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
