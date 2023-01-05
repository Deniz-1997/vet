<?php

namespace App\Entity\Form;

use App\Entity\Appointment\AppointmentFormTemplate;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Table(schema="form")
 * @ORM\Entity(repositoryClass="App\Repository\FormFieldValueRepository")
 */
class FormFieldValue
{
    use OrmIdTrait;

    /**
     * @var AppointmentFormTemplate|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment\AppointmentFormTemplate", inversedBy="formFieldValues")
     */
    private ?AppointmentFormTemplate $appointmentFormTemplate = null;

    /**
     * @var FormField
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Form\FormField")
     * @ORM\JoinColumn(name="form_field_id", referencedColumnName="id", nullable=false)
     * @SWG\Property(description="Определяет поле")
     */
    private FormField $formField;

    /**
     * @var string Значение
     * @Groups({"default"})
     * @SWG\Property(description="Значение")
     * @ORM\Column(type="text", nullable=true)
     */
    private string $value;

    /**
     * @var array|null
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"default"})
     * @SWG\Property(description="Дополнительный данные JSON")
     */
    private ?array $extraData = null;


    /**
     * @return AppointmentFormTemplate|null
     */
    public function getAppointmentFormTemplate(): ?AppointmentFormTemplate
    {
        return $this->appointmentFormTemplate;
    }

    /**
     * @param AppointmentFormTemplate|null $appointmentFormTemplate
     * @return $this
     */
    public function setAppointmentFormTemplate(?AppointmentFormTemplate $appointmentFormTemplate): self
    {
        $this->appointmentFormTemplate = $appointmentFormTemplate;

        return $this;
    }

    /**
     * @return FormField|null
     */
    public function getFormField(): ?FormField
    {
        return $this->formField;
    }

    /**
     * @param FormField|null $formField
     * @return $this
     */
    public function setFormField(?FormField $formField): self
    {
        $this->formField = $formField;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return FormFieldValue
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
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
     * @return FormFieldValue
     */
    public function setExtraData(?array $extraData): FormFieldValue
    {
        $this->extraData = $extraData;
        return $this;
    }
}
