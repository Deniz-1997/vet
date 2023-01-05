<?php

namespace App\Entity\Reference;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmExternalIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\PetTypeRepository")
 * @ORM\Table("reference_pet_types", schema="reference")
 */
class PetType
{
    use OrmSortTrait, OrmReferenceTrait, OrmExternalIdTrait;

    /**
     * @var bool
     * @Groups("default")
     * @SWG\Property(description="Удален", type="boolean", default=false)
     */
    private $isDeleted = false;
    /**
     * @var Icon|null Страна
     * @Groups ({"default"})
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\Icon")
     * @ORM\JoinColumn(nullable=true)
     */
    private $icon;

    /**
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->deleted;
    }

    public function getIcon():?Icon
    {
        return $this->icon;
    }

    public function setIcon(Icon $icon): self
    {
        $this->icon = $icon;
        return $this;
    }
}
