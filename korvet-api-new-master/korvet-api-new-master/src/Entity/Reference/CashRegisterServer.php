<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmUuidTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmDeletedTrait;

/**
 * @ORM\Table(schema="cash")
 * @ORM\Entity()
 */
class CashRegisterServer
{
    use OrmUuidTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var object Подразделение, в котором установлен ККМ-сервер
     * @Groups({"default"})
     * @SWG\Property(description="Подразделение, в котором установлен ККМ-сервер")
     * @Assert\NotBlank(message="organization.unit.not_blank")
     */
    private $unit;

    /**
     * @return object
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param object $unit
     */
    public function setUnit($unit): void
    {
        $this->unit = $unit;
    }
}
