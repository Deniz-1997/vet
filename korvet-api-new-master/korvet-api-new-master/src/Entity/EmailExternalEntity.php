<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="webslon_email_email_external_entity")
 */
class EmailExternalEntity
{
    use OrmIdTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Email")
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @return mixed
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param Email $email
     *
     * @return EmailExternalEntity
     */
    public function setEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return EmailExternalEntity
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
