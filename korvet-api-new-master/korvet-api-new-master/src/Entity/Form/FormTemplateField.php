<?php

namespace App\Entity\Form;

use App\Entity\Reference\FormFieldType;
use App\Entity\Reference\FormTemplate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmSortTrait;

/**
 * @ORM\Table(schema="form")
 * @ORM\Entity(repositoryClass="App\Repository\Form\FormTemplateFieldRepository")
 */
class FormTemplateField
{
    use OrmIdTrait, OrmSortTrait;

    /**
     * @var FormTemplate|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\FormTemplate", inversedBy="formFields")
     * @ORM\JoinColumn(name="form_template_id", referencedColumnName="id", nullable=true)
     */
    private $formTemplate;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Описание характеристики поля", type="string")
     */
    private $description;

    /**
     * @var FormFieldType
     * @Groups("default")
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\FormFieldType")
     * @ORM\JoinColumn(name="field_type_id", referencedColumnName="id", nullable=false)
     */
    private $type;

    /**
     * @var FormFieldPropertyValue[]|null
     * @Groups("default")
     * @ORM\OneToMany(targetEntity="App\Entity\Form\FormFieldPropertyValue", mappedBy="formField", cascade={"all"}, orphanRemoval=true)
     * @SWG\Property( @SWG\Items(ref=@Model(type=FormFieldPropertyValue::class)), type="array", description="Значения свойств для поля")
     */
    private $properties;

    /**
     * @var FormField[]|null
     * @Groups("default")
     * @ORM\OneToMany(targetEntity="App\Entity\Form\FormField", mappedBy="formTemplateField", cascade={"all"}, orphanRemoval=true)
     * @SWG\Property( @SWG\Items(ref=@Model(type=FormField::class)), type="array", description="Поля, созданные по данному шаблону")
     */
    private $fields;

    /**
     * @var array|null
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"default"})
     * @SWG\Property(description="Дополнительный данные JSON")
     */
    private $extraData;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"default"})
     * @SWG\Property(description="Название поля")
     */
    private  $name;

    /**
     * FormField constructor.
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return FormTemplateField
     */
    public function setDescription(?string $description): FormTemplateField
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return FormFieldType
     */
    public function getType(): FormFieldType
    {
        return $this->type;
    }

    /**
     * @param FormFieldType $type
     * @return FormTemplateField
     */
    public function setType(FormFieldType $type): FormTemplateField
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param FormFieldPropertyValue $property
     * @return FormTemplateField
     */
    public function addProperty(FormFieldPropertyValue $property): FormTemplateField
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setFormField($this);
        }
        return $this;
    }

    /**
     * @param FormFieldPropertyValue $property
     * @return FormTemplateField
     */
    public function removeProperty(FormFieldPropertyValue $property): FormTemplateField
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
        }
//        if ($property && ($property->getFormField() === $this)) {
//            $property->setFormField(null);
//        }
        return $this;
    }

    /**
     * @return FormFieldPropertyValue[]|null|Collection
     */
    public function getProperties(): ?Collection
    {
        return $this->properties;
    }

    /**
     * @return FormTemplate|null
     */
    public function getFormTemplate(): ?FormTemplate
    {
        return $this->formTemplate;
    }

    /**
     * @param FormTemplate|null $formTemplate
     * @return FormTemplateField
     */
    public function setFormTemplate(?FormTemplate $formTemplate): FormTemplateField
    {
        $this->formTemplate = $formTemplate;
        return $this;
    }

    /**
     * @param FormField $field
     * @return FormTemplateField
     */
    public function addField(FormField $field): FormTemplateField
    {
        if (!$this->properties->contains($field)) {
            $this->properties[] = $field;
            $field->setFormTemplateField($this);
        }
        return $this;
    }

    /**
     * @param FormField $field
     * @return FormTemplateField
     */
    public function removeField(FormField $field): FormTemplateField
    {
        if ($this->properties->contains($field)) {
            $this->properties->removeElement($field);
        }

        return $this;
    }

    /**
     * @param FormField[]|null $fields
     * @return FormTemplateField
     */
    public function setFields(?array $fields): FormTemplateField
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getExtraData(): ?array
    {
        return $this->extraData;
    }

    /**
     * @param array|null $extraData
     * @return FormTemplateField
     */
    public function setExtraData(?array $extraData): FormTemplateField
    {
        $this->extraData = $extraData;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return FormTemplateField
     */
    public function setName(?string $name): FormTemplateField
    {
        $this->name = $name;
        return $this;
    }
}
