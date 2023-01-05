<?php

namespace App\Entity\Form;

use App\Entity\Reference\FormTemplate;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Table(schema="form")
 * @ORM\Entity(repositoryClass="App\Repository\Form\FormFieldRepository")
 */
class FormField
{
    use OrmIdTrait, OrmNameTrait, OrmSortTrait;

    /**
     * @var FormTemplateField
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Form\FormTemplateField", inversedBy="field")
     * @ORM\JoinColumn(name="field_template_id", referencedColumnName="id", nullable=false)
     */
    private FormTemplateField $formTemplateField;

    /**
     * @var FormTemplate|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\FormTemplate", inversedBy="fields")
     * @ORM\JoinColumn(name="form_template_id", referencedColumnName="id", nullable=true)
     */
    private ?FormTemplate $formTemplate = null;

    /**
     * @var bool
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean", description="Обязательное поле для заполнения или нет")
     */
    private bool $isRequired = false;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Описание поля")
     */
    private ?string $description = null;

    /**
     * @return FormTemplateField
     */
    public function getFormTemplateField(): FormTemplateField
    {
        return $this->formTemplateField;
    }

    /**
     * @param FormTemplateField $formTemplateField
     * @return FormField
     */
    public function setFormTemplateField(FormTemplateField $formTemplateField): FormField
    {
        $this->formTemplateField = $formTemplateField;
        return $this;
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
     * @return FormField
     */
    public function setFormTemplate(?FormTemplate $formTemplate): FormField
    {
        $this->formTemplate = $formTemplate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     * @return FormField
     */
    public function setIsRequired(bool $isRequired): FormField
    {
        $this->isRequired = $isRequired;
        return $this;
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
     * @return FormField
     */
    public function setDescription(?string $description): FormField
    {
        $this->description = $description;
        return $this;
    }


}
