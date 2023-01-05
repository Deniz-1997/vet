<?php


namespace App\Entity\Form;

use App\Entity\Reference\FormFieldProperty;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(schema="form")
 *@ORM\Entity(repositoryClass="App\Repository\Form\FormFieldPropertyValueRepository")
 */
class FormFieldPropertyValue
{
    use OrmIdTrait;

    /**
     * @var FormFieldProperty
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\FormFieldProperty")
     * @ORM\JoinColumn(name="form_field_property_id", referencedColumnName="id")
     */
    private FormFieldProperty $formFieldProperty;

    /**
     * @var FormTemplateField|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Form\FormTemplateField", inversedBy="properties")
     * @ORM\JoinColumn(name="form_field_id", referencedColumnName="id")
     */
    private ?FormTemplateField $formField = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Значение свойства")
     */
    private ?string $value = null;

    /**
     * @return FormFieldProperty
     */
    public function getFormFieldProperty(): FormFieldProperty
    {
        return $this->formFieldProperty;
    }

    /**
     * @param FormFieldProperty $formFieldProperty
     * @return FormFieldPropertyValue
     */
    public function setFormFieldProperty(FormFieldProperty $formFieldProperty): FormFieldPropertyValue
    {
        $this->formFieldProperty = $formFieldProperty;
        return $this;
    }

    /**
     * @return FormTemplateField
     */
    public function getFormField(): FormTemplateField
    {
        return $this->formField;
    }

    /**
     * @param FormTemplateField|null $formField
     * @return FormFieldPropertyValue
     */
    public function setFormField(FormTemplateField $formField): ?FormFieldPropertyValue
    {
        $this->formField = $formField;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return FormFieldPropertyValue
     */
    public function setValue(?string $value): FormFieldPropertyValue
    {
        $this->value = $value;
        return $this;
    }


}
