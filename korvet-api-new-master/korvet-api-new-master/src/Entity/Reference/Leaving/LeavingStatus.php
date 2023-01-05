<?php

namespace App\Entity\Reference\Leaving;

use App\Repository\Reference\Leaving\LeavingStatusRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Leaving\LeavingStatusRepository")
 * @ORM\Table ("leaving_status", schema="reference")
 */
class LeavingStatus
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var string
     *
     * @Groups({"default"})
     *
     * @ORM\Column(type="string")
     */
    private  $color;

    /**
     * @var string
     *
     * @Groups({"default"})
     *
     * @ORM\Column(type="string", options={"default"="null"})
     */
    private $code;

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return LeavingStatus
     */
    public function setColor(string $color): self
    {
        $this->color = $color;
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
     * @return LeavingStatus
     */
    public function setCode(string $code): self
    {
        $this->code =$code;
        return $this;
    }
}
