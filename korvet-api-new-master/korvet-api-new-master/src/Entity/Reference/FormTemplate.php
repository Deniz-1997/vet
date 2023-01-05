<?php

namespace App\Entity\Reference;

use App\Entity\Form\FormField;
use App\Entity\Form\FormTemplateField;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmActiveTrait;
use App\Traits\ORMTraits\OrmCodeTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(schema="form")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\FormTemplateRepository")
 */
class FormTemplate
{
    use OrmIdTrait, OrmCodeTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var string Наименование
     *
     * @Groups("default")
     *
     * @ORM\Column(type="string", nullable=false, options={"default": ""})
     * @SWG\Property(description="Наименование", type="string")
     * @Assert\NotBlank(message="name.not_blank")
     */
    private string $name = '';

    /**
     * @var FormTemplateField[]
     * @Groups("api.formTemplate")
     * @ORM\OrderBy({"sort" = "ASC"})
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Form\FormTemplateField",
     *     mappedBy="formTemplate",
     *     cascade={"all"},
     *     orphanRemoval=true
     * )
     * @SWG\Property(
     *     @SWG\Items( ref=@Model(type=FormTemplateField::class) ),
     *     type="array",
     *     description="Шаблоны полей для формы"
     * )
     */
    private $formFields;

    /**
     * @var FormField[]
     * @Groups("api.formTemplate")
     * @ORM\OneToMany(targetEntity="App\Entity\Form\FormField", mappedBy="formTemplate", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"sort" = "ASC"})
     * @SWG\Property( @SWG\Items( ref=@Model(type=FormField::class) ), type="array", description="Поля формы")
     */
    private $fields;

    /**
     * @var string|null Текст для шаблона
     * @Groups({"default"})
     * @SWG\Property(description="Описание шаблона")
     * @ORM\Column(type="text", nullable=true)
     */
    private $template;

    /**
     * @var integer
     * @Assert\NotNull()
     * @Groups("default")
     * @ORM\Column(type="integer", options={"default":0})
     * @SWG\Property(type="integer", description="Кол-во полей в форме")
     */
    private $fieldsQuantity = 0;

    /**
     * @var integer
     * @Assert\NotNull()
     * @Groups("default")
     * @ORM\Column(type="integer", options={"default":0})
     * @SWG\Property(type="integer", description="Кол-во использований в приеме")
     */
    private $appointmentCount = 0;

    /**
     * @var integer
     * @Assert\NotNull()
     * @Groups("default")
     * @ORM\Column(type="integer", options={"default":0})
     * @SWG\Property(type="integer", description="Кол-во использований в выезде")
     */
    private $leavingCount = 0;

    /**
     * @var integer|null
     * @Groups("default")
     * @ORM\Column(type="integer", nullable=true)
     * @SWG\Property(type="integer", description="Родитель клонирования")
     */
    private $parent;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default": true})
     * @Groups({"default"})
     * @SWG\Property(description="Архив", type="boolean", default=true)
     */
    private $active = true;

    /**
     * FormTemplate constructor.
     */
    public function __construct()
    {
        $this->formFields = new ArrayCollection();
        $this->fields = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getFormFields(): Collection
    {
        return $this->formFields;
    }

    /**
     * @param FormTemplateField $formField
     * @return $this
     */
    public function addFormField(FormTemplateField $formField): FormTemplate
    {
        if (!$this->formFields->contains($formField)) {
            $this->formFields[] = $formField;
            $formField->setFormTemplate($this);
        }

        return $this;
    }

    /**
     * @param FormTemplateField $formField
     * @return FormTemplate
     */
    public function removeFormField(FormTemplateField $formField): FormTemplate
    {
        if ($this->formFields->contains($formField)) {
            $this->formFields->removeElement($formField);
        }
        if ($formField->getFormTemplate() === $this) {
            $formField->setFormTemplate(null);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @param FormField $formField
     * @return $this
     */
    public function addField(FormField $formField): FormTemplate
    {
        if (!$this->formFields->contains($formField)) {
            $this->formFields[] = $formField;
            $formField->setFormTemplate($this);
        }

        return $this;
    }

    /**
     * @param FormField $formField
     * @return FormTemplate
     */
    public function removeField(FormField $formField): FormTemplate
    {
        if ($this->formFields->contains($formField)) {
            $this->formFields->removeElement($formField);
        }
        if ($formField->getFormTemplate() === $this) {
            $formField->setFormTemplate(null);
        }
        return $this;
    }

    /**
     * @return FormTemplate
     */
    public function removeFields(): FormTemplate
    {
        foreach ($this->fields as $field) {
            $this->removeField($field);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getFieldsQuantity(): int
    {
        return $this->fieldsQuantity;
    }

    /**
     * @param int $fieldsQuantity
     * @return FormTemplate
     */
    public function setFieldsQuantity(int $fieldsQuantity): FormTemplate
    {
        $this->fieldsQuantity = $fieldsQuantity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     * @return FormTemplate
     */
    public function setTemplate(?string $template): FormTemplate
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FormTemplate
     */
    public function setName(string $name): FormTemplate
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getAppointmentCount(): int
    {
        return $this->appointmentCount;
    }

    /**
     * @param int $appointmentCount
     * @return FormTemplate
     */
    public function setAppointmentCount(int $appointmentCount): FormTemplate
    {
        $this->appointmentCount = $appointmentCount;
        return $this;
    }

    public function getLeavingCount(): int
    {
        return $this->leavingCount;
    }

    /**
     * @param int $leavingCount
     * @return FormTemplate
     */
    public function setLeavingCount(int $leavingCount): FormTemplate
    {
        $this->leavingCount = $leavingCount;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getParent(): ?int
    {
        return $this->parent;
    }

    /**
     * @param int|null $parent
     * @return FormTemplate
     */
    public function setParent(?int $parent): FormTemplate
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

}
