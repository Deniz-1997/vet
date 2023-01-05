<?php

namespace App\Entity;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * Class Settings
 * 
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 * @ORM\Table(name="settings")
 */
class Settings
{
    use OrmIdTrait, OrmSortTrait;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", unique=true)
     */
    private $key;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Settings
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Settings
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
    
        return $this;
    }
}
