<?php

namespace App\Entity\Appointment;

use App\Entity\Form\FormFieldValue;
use App\Entity\Leaving\Leaving;
use App\Entity\Reference\FormTemplate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\Appointment\AppointmentFormTemplateRepository")
 */
class AppointmentFormTemplate
{
    use OrmIdTrait;

    /**
     * @var Appointment
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment\Appointment", inversedBy="appointmentFormTemplate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $appointment;
    /**
     * @var Leaving
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Leaving\Leaving", inversedBy="appointmentFormTemplate")
     * @ORM\JoinColumn(nullable=true)
     */
    private $leaving;

    /**
     * @var FormFieldValue[]|null
     * @Groups({"default"})
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Form\FormFieldValue",
     *     mappedBy="appointmentFormTemplate",
     *     cascade={"all"},
     *     orphanRemoval=true
     *     )
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $formFieldValues;

    /**
     * @var FormTemplate
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\FormTemplate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formTemplate;

    /**
     * AppointmentFormTemplate constructor.
     */
    public function __construct()
    {
        $this->formFieldValues = new ArrayCollection();
    }

    /**
     * @return Appointment|null
     */
    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    /**
     * @param Appointment|null $appointment
     * @return $this
     */
    public function setAppointment(?Appointment $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }

    /**
     * @return Leaving|null
     */
    public function getLeaving(): ?Leaving
    {
        return $this->leaving;
    }

    /**
     * @param Leaving|null $leaving
     * @return $this
     */
    public function setLeaving(?Leaving $leaving): self
    {
        $this->leaving = $leaving;

        return $this;
    }

    /**
     * @return Collection|FormFieldValue[]
     */
    public function getFormFieldValues(): Collection
    {
        return $this->formFieldValues;
    }

    /**
     * @param FormFieldValue $formFieldValue
     * @return $this
     */
    public function addFormFieldValue(FormFieldValue $formFieldValue): self
    {
        if (!$this->formFieldValues->contains($formFieldValue)) {
            $this->formFieldValues[] = $formFieldValue;
            $formFieldValue->setAppointmentFormTemplate($this);
        }

        return $this;
    }

    /**
     * @param FormFieldValue $formFieldValue
     * @return $this
     */
    public function removeFormFieldValue(FormFieldValue $formFieldValue): self
    {
        if ($this->formFieldValues->contains($formFieldValue)) {
            $this->formFieldValues->removeElement($formFieldValue);
            // set the owning side to null (unless already changed)
            if ($formFieldValue->getAppointmentFormTemplate() === $this) {
                $formFieldValue->setAppointmentFormTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return FormTemplate
     */
    public function getFormTemplate(): FormTemplate
    {
        return $this->formTemplate;
    }

    /**
     * @param FormTemplate $formTemplate
     */
    public function setFormTemplate(FormTemplate $formTemplate): void
    {
        $this->formTemplate = $formTemplate;
    }




}
