<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\DocBlock\Tags\Version;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmUuidTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VeterinaryPassportTypeRepository")
 * @ORM\Table(name="reference_veterinary_passport_type", schema="reference")
 */
class VeterinaryPassportType
{
    use OrmUuidTrait, OrmNameTrait, OrmDeletedTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmSortTrait;

    /**
     * @var bool|null
     * @Groups({"default"})
     * @ORM\Column(type="boolean")
     */
    private $isDefault = false;

    /**
     * @var string|null Маска для валидации на бэкенде
     * @Groups({"default"})
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $numberMask;

    /**
     * @var string|null Маска для валидации на фронте
     * @Groups({"default"})
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $numberMaskFront;

    /**
     * @return bool|null
     */
    public function getIsDefault()
    {
        return $this->isDefault ?? false;
    }

    /**
     * @param bool|null $isDefault
     *
     * @return VeterinaryPassportType
     */
    public function setIsDefault(?bool $isDefault)
    {
        $this->isDefault = (bool)$isDefault;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumberMask()
    {
        return $this->numberMask;
    }

    /**
     * @param string|null $numberMask
     *
     * @return VeterinaryPassportType
     */
    public function setNumberMask($numberMask)
    {
        $this->numberMask = $numberMask;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumberMaskFront(): ?string
    {
        return $this->numberMaskFront;
    }

    /**
     * @param string|null $numberMaskFront
     * @return VeterinaryPassportType
     */
    public function setNumberMaskFront(?string $numberMaskFront): VeterinaryPassportType
    {
        $this->numberMaskFront = $numberMaskFront;
        return $this;
    }
}
