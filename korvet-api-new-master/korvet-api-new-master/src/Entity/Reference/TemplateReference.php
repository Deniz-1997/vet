<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmNameTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\TemplateReferenceRepository")
 */
class TemplateReference
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var TemplateReference[]
     * @Groups("default")
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Reference\TemplateReferenceValue",
     *     mappedBy="templateReference", cascade={"all"}, orphanRemoval=true
     *     )
     * @SWG\Property(
     *     @SWG\Items( ref=@Model(type=TemplateReferenceValue::class) ),
     *     type="array", description="Значения справочника"
     * )
     */
    private $referenceValues;

    /**
     * @var boolean
     * @Groups("default")
     * @SWG\Property(description="Удален", type="boolean", default=false)
     */
    private $isDeleted = false;

    public function __construct()
    {
        $this->referenceValues = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getReferenceValues(): Collection
    {
        return $this->referenceValues;
    }

    /**
     * @param TemplateReferenceValue $templateReferenceValue
     * @return TemplateReference
     */
    public function addReferenceValue(TemplateReferenceValue $templateReferenceValue): TemplateReference
    {
        if (!$this->referenceValues->contains($templateReferenceValue)) {
            $this->referenceValues[] = $templateReferenceValue;
            $templateReferenceValue->setTemplateReference($this);
        }

        return $this;
    }

    /**
     * @param TemplateReferenceValue $templateReferenceValue
     * @return TemplateReference
     */
    public function removeReferenceValue(TemplateReferenceValue $templateReferenceValue): TemplateReference
    {
        if ($this->referenceValues->contains($templateReferenceValue)) {
            $this->referenceValues->removeElement($templateReferenceValue);
        }
        if ($templateReferenceValue->getTemplateReference() === $this) {
            $templateReferenceValue->setTemplateReference(null);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->deleted;
    }
}
