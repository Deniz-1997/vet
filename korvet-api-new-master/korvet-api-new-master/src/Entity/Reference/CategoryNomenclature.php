<?php

namespace App\Entity\Reference;


use App\Packages\Annotation\IgnoreDeleted;
use App\Packages\Annotation\SerializeNestedIgnore;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Table ("reference_category_nomenclature", schema="reference")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\CategoryNomenclatureRepository")
 */
class CategoryNomenclature
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @AppAssert\MaxParent(maxParentLevel=3)
     * @IgnoreDeleted()
     * @Groups({"default"})
     * @var CategoryNomenclature |null
     * @ORM\JoinColumn(nullable=true)
     * @SerializeNestedIgnore()
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\CategoryNomenclature")
     */
    private  $parent;

    /**
     * @return CategoryNomenclature|null
     */
    public function getParent(): ?CategoryNomenclature
    {
        return $this->parent;
    }

    /**
     * @param CategoryNomenclature|null $parent
     * @return $this
     */
    public function setParent(?CategoryNomenclature $parent): self
    {
        $this->parent = $parent;
        return $this;
    }


}
