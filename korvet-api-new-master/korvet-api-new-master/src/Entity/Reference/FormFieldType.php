<?php


namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmCodeTrait;
use App\Traits\ORMTraits\OrmDescriptionTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmNameTrait;

/**
 * @ORM\Table(schema="form")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\FormFieldTypeRepository")
 */
class FormFieldType
{
    use OrmIdTrait, OrmNameTrait, OrmCodeTrait, OrmDescriptionTrait, OrmSortTrait;

    /**
     * @var FormFieldProperty[]|null
     * @Groups("default")
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\FormFieldProperty", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(
     *     name="form.form_field_form_property",
     *     joinColumns={@ORM\JoinColumn(name="field_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id")}
     * )
     * @ORM\OrderBy({"sort" = "ASC"})
     * @SWG\Property( @SWG\Items(ref=@Model(type=FormFieldProperty::class)), type="array", description="Свойства поля")
     */
    private $properties;

    /**
     * FormFieldType constructor.
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function addProperty(FormFieldProperty $property): FormFieldType
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
        }
        return $this;
    }

    public function removeProperty(FormFieldProperty $property): FormFieldType
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
        }
        return $this;
    }

    /**
     * @return FormFieldProperty[]|null|Collection
     */
    public function getProperties(): ?Collection
    {
        return $this->properties;
    }
}
