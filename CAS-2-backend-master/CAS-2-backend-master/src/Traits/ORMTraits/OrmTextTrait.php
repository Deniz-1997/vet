<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;

trait OrmTextTrait
{
    /**
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     *
     * @var string
     * @ORM\Column(type="text")
     * @SWG\Property(description="Текст", type="string")
     */
    private string $text;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
