<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailThemeRepository")
 * @ORM\Table(name="webslon_email_theme")
 */
class Theme extends Resource
{
    use OrmIdTrait;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDefault = false;

    /**
     * @param bool $deleted
     *
     * @return Theme
     */
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @param bool $isDefault
     *
     * @return Theme
     */
    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted === true;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault === true;
    }
}
