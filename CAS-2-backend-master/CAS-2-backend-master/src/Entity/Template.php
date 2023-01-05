<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailTemplateRepository")
 * @ORM\Table(name="webslon_email_template")
 */
class Template extends Resource
{
    use OrmIdTrait;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="Theme")
     * @ORM\JoinColumn(nullable=true)
     */
    private $theme;

    /**
     * @return boolean
     */
    public function isDeleted(): bool
    {
        return $this->deleted === true;
    }

    /**
     * @param bool $deleted
     *
     * @return Template
     */
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return null|Theme
     */
    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    /**
     * @param null|Theme $theme
     * @return Template
     */
    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTheme(): bool
    {
        return $this->theme !== null;
    }
}
