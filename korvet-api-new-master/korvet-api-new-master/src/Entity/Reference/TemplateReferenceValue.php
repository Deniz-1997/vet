<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmNameTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\TemplateReferenceValueRepository")
 */
class TemplateReferenceValue
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var TemplateReference
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\TemplateReference", inversedBy="referenceValues")
     * @ORM\JoinColumn(name="template_reference_id", referencedColumnName="id", nullable=false)
     */
    private $templateReference;


    /**
     * @var boolean
     * @Groups("default")
     * @SWG\Property(description="Удален", type="boolean", default=false)
     */
    private $isDeleted = false;

    /**
     * @return TemplateReference
     */
    public function getTemplateReference(): TemplateReference
    {
        return $this->templateReference;
    }

    /**
     * @param TemplateReference
     * @return TemplateReferenceValue
     */
    public function setTemplateReference(TemplateReference $templateReference): self
    {
        $this->templateReference = $templateReference;
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
