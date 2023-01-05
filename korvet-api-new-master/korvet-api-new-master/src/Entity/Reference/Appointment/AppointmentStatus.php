<?php

namespace App\Entity\Reference\Appointment;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Appointment\AppointmentStatusRepository")
 */
class AppointmentStatus
{
    use OrmReferenceTrait, OrmExternalIdTrait, OrmSortTrait;

    /**
     * @var string
     *
     * @Groups({"default"})
     *
     * @ORM\Column(type="string")
     */
    private $color;

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
     */
    public function setColor(string $color)
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
     */
    public function setCode(string $code)
    {
        $this->code =$code;
        return $this;
    }
}
