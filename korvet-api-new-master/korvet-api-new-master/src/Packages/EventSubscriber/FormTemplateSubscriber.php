<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Appointment\AppointmentFormTemplate;
use App\Entity\Form\FormField;
use App\Entity\Form\FormTemplateField;
use App\Entity\Reference\FormTemplate;
use App\Service\FormBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Exception\ApiException;


/**
 * Class FormTemplateSubscriber
 */
class FormTemplateSubscriber implements EventSubscriberInterface
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * FormTemplateSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'onBeforeProcessAppEntityReferenceFormTemplatePatch' => 'onBeforeSaveFormTemplate',
            'onBeforeProcessAppEntityReferenceFormTemplatePut' => 'onBeforeSaveFormTemplate',
            'onBeforeProcessAppEntityReferenceFormTemplatePost' => 'onBeforeProcessFormTemplatePost',
            'onBeforeProcessEntityAppEntityReferenceFormTemplatePut' => 'onBeforeProcessEntityPatch',
            'onBeforeProcessEntityAppEntityReferenceFormTemplatePatch' => 'onBeforeProcessEntityPatch'
        );
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function onBeforeProcessEntityPatch(EventRequest $event)
    {
        /** @var FormTemplate $formTemplate */
        $formTemplate = $event->getData();

        //      Проверка на уникальность имени
        $name = $formTemplate->getName();
        $name = trim($name);
        $name= preg_replace("/  +/"," ",$name);
        $formTemplates = $this->entityManager->getRepository(FormTemplate::class)->findBy(['name' => $name, 'deleted' => false, 'active' => true]);
        if ($formTemplate->isActive()) {
            /** @var FormTemplate $template */
            foreach ($formTemplates as $template) {
                if ($template->getId() != $formTemplate->getId()) {
                    throw new ApiException('formTemplate.formTemplate_name_already_exist', '', null, 400);
                }
            }
        }
//      Проверка на удаление уже использованного в приеме шаблона
        if ($formTemplate->isDeleted()) {
            /** @var AppointmentFormTemplate[] */
            $appointmentFormTemplates = $this->entityManager->getRepository(AppointmentFormTemplate::class)
                ->findBy([(['formTemplate' => $formTemplate->getId()])]);
            if (count($appointmentFormTemplates) != 0) {
                throw new ApiException('formTemplate.cannot_delete_used_in_appointment', '', null, 400);
            }
        }
//      Проверка на архив уже использованного в приеме шаблона
//        /** @var FormTemplate $formTemplateCurrent */
//        $formTemplateCurrent = $this->entityManager->getRepository(FormTemplate::class)->findOneBy(['id' => $formTemplate->getId()]);
//        if (!$formTemplate->isActive()) {
//            if ($formTemplateCurrent->getAppointmentCount() > 0) {
//                throw new ApiException('formTemplate.cannot_deactivate_used_in_appointment', '', null, 400);
//            }
//        }

        $formTemplate->removeFields();

        // Поиск вхождений паттерна {{{X-Y-Z}}} в шаблон формы
        preg_match_all('/{{{(.*?)}}}/', $formTemplate->getTemplate(), $templateFields);

        if (!empty($templateFields[1])) {
            $formTemplateFields = $formTemplate->getFormFields();
            foreach ($templateFields[1] as $key => $val) {
                $item = explode('-', $val);
                if (count($item) == 2) {
                    array_push($item, null);
                }
                list($x, $y, $z) = $item;

                /** @var FormTemplateField $formTemplateField */
                foreach ($formTemplateFields as $formTemplateField) {
                    if ($formTemplateField->getSort() === (int)$x) {
                        $formField = (new FormField())
                            ->setFormTemplateField($formTemplateField)
                            ->setFormTemplate($formTemplate)
                            ->setName($val)
                            ->setIsRequired((int)$z === 1)
                            ->setSort($key + 1);
                        $this->entityManager->persist($formField);
                    }
                }
            }
        }
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function onBeforeSaveFormTemplate(EventRequest $event)
    {
        $inf = $event->getData();
        $data = json_decode($inf['content'], true);

        // Проверяем шаблон
        $formBuilderService = new FormBuilderService($this->entityManager);
        $result = $formBuilderService->checkTemplate($data);

        foreach ($data['formFields'] as $formFieldKey => $formField) {
            foreach ($formField['properties'] as $propertyKey => $property) {
                $value = strval(
                    \is_array($property['value'])
                    ? json_encode($property['value'], JSON_UNESCAPED_UNICODE)
                    : $property['value']
                );
                $property['value'] = $value;
                $data['formFields'][$formFieldKey]['properties'][$propertyKey]['value'] = $value;
            }
        }
        $event->setData(json_encode($data));
    }

    /**
     * @param EventRequest $event
     * @throws ApiException
     */
    public function onBeforeProcessFormTemplatePost(EventRequest $event)
    {
        $formTemplate = json_decode($event->getData());
        $name = $formTemplate->name;
        $name = trim($name);
        $name= preg_replace("/  +/"," ",$name);
        $formTemplates = $this->entityManager->getRepository(FormTemplate::class)
                            ->findBy(['name' => $name, 'deleted' => false, 'active' => true]);
        if (!empty($formTemplates)) {
            throw new ApiException('formTemplate.formTemplate_name_already_exist','', null, 400);
        }
    }


}
