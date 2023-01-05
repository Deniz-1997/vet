<?php

namespace App\Entity\Shop;

use App\Entity\Notifications\NotificationsList;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use OpenApi\Annotations as SWG;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Reference\Unit;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(schema="shop")
 * @ORM\Entity(repositoryClass="App\Repository\Shop\ShopSettingsRepository")
 * @UniqueEntity(
 *     fields={"unit"} ,
 *     message="Значение должно быть уникально"
 * )
 */
class ShopSettings
{
    use OrmIdTrait, OrmNameTrait, OrmSortTrait;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="json")
     */
    private array $data = [];

    /**
     * @var Unit Клиника
     * @Groups({"default"})
     * @SWG\Property(description="Клиника")
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Unit")
     */
    private $unit;

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data ;
    }

    /**
     * @param array data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Unit
     */
    public function getUnit(): Unit
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     * @return ShopSettings
     */
    public function setUnit(Unit $unit): self
    {
        $this->unit = $unit;
        return $this;
    }
}
