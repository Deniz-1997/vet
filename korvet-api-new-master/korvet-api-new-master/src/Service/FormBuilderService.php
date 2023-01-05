<?php

namespace App\Service;

use App\Entity\Form\FormField;
use App\Entity\Form\FormFieldPropertyValue;
use App\Entity\Form\FormTemplateField;
use App\Entity\Reference\FormTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\ApiException;

/**
 * Class FormBuilderService
 */
class FormBuilderService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $formTemplate
     * @return FormField[]|object[]
     * @throws ApiException
     */
    public function createFields(array $formTemplate): array
    {
        $templateFields = [];
        if (!empty($formTemplate['template'])) {
            $templateFields = $this->checkTemplate($formTemplate);
        }

        if (!empty($formTemplate['template']) && !empty($formTemplate['formFields'])) {
            // Поиск вхождений паттерна {{{X-Y-Z}}} в шаблон формы
            preg_match_all('/{{{(.*?)}}}/', $formTemplate['template'], $templateFields);
            $templateFields = $this->checkTemplate($formTemplate);
            $formFields = [];
            $countFields = 0;

            $formTemplateFields = $formTemplate['formFields'];
            foreach ($templateFields as $templateField) {
                $formField = [
                    'name' => $templateField,
                    'formTemplate' => $formTemplate['id'],
                    'sort' => ++$countFields,
                    'isRequired' => false
                ];
                $fieldCode = explode('-', $templateField);
                foreach ($fieldCode as $key => $item) {
                    if ($key == 0) {
                        foreach ($formTemplateFields as $formTemplateField) {
                            if ($formTemplateField['sort'] == $item) {
                                $formField['formTemplateField'] = $formTemplateField;
                                break;
                            }
                        }
                    } elseif ($key == 2) {
                        $formField['isRequired'] = true;
                    }
                }
                $formFields[] = $formField;
            }

            $formTemplate['fields'] = $formFields;

            // Копирование данных о родительских справочниках
            foreach ($formTemplate['fields'] as $key => $val) {
                $formTemplate['fields'][$key]['parentReference'] = $val['formTemplateField']['parentReference'] ?? null;
                $formTemplate['fields'][$key]['subParentReference'] = $val['formTemplateField']['subParentReference'] ?? null;
                $formTemplate['fields'][$key]['viewType'] = $val['formTemplateField']['viewType'] ?? null;
                $formTemplate['fields'][$key]['filter'] = $val['formTemplateField']['filter'] ?? null;
            }
        }

        return $formTemplate;
    }

    /**
     * @param array $formTemplate
     * @return array
     * @throws ApiException
     */
    public function checkTemplate(array $formTemplate): array
    {
//        Заполняем массив всеми порядковыми номерами formTemplateField
        $templateFieldsSort = [];
        foreach ($formTemplate['formFields'] as $formField) {
            $templateFieldsSort[$formField['sort']] = [];
        }

        preg_match_all('/{{{(.*?)}}}/', $formTemplate['template'], $templateFields);
        $templateFields = !empty($templateFields) ? $templateFields[1] : [];
        $fieldsTemplate = [];
        $errors = [];
        foreach ($templateFields as $templateField) {
            $fieldTemplate = explode('-', $templateField);
            $fieldSort = $fieldTemplate[0];
            if (count($fieldTemplate) < 2 || count($fieldTemplate) > 3) {
                $errors[] = [
                    'item' => '{{{' . $templateField . '}}}',
                    'message' => 'Неверное количество параметров в шаблоне'
                ];
                continue;
//                new ApiException('Неверное количество параметров в шаблоне', '', null, 400);
            }
            foreach ($fieldTemplate as $key => $item) {
                if (!is_numeric($item)) {
                    $errors[] = [
                        'item' => '{{{' . $templateField . '}}}',
                        'message' => 'Форма вставки шаблона должна иметь числовые значения'
                    ];
                    break;
//                    new ApiException('Неверно указан номер шаблона', '', null, 400);
                }
                switch ($key) {
                    case 0:
                        if (!array_key_exists(intval($item), $templateFieldsSort)) {
                            $errors[] = [
                                'item' => '{{{' . $templateField . '}}}',
                                'message' => 'Не найден порядковый номер шаблонного поля'
                            ];
                            break 2;
                        }
                        break;
                    case 1:
                        if (in_array(intval($item), $templateFieldsSort[$fieldSort])) {
                            $errors[] = [
                                'item' => '{{{' . $templateField . '}}}',
                                'message' => 'Дублируется параметр Y'
                            ];
                        } else {
                            array_push($templateFieldsSort[$fieldSort], intval($item));
                        }
                        break;
                    case 2:
                        if (intval($item) != 1) {
                            $errors[] = [
                                'item' => '{{{' . $templateField . '}}}',
                                'message' => 'Параметр Z может быть равен только 1 или отсутствовать'
                            ];
                        }
                        break;
                }
            }
            $fieldsTemplate[] = $templateField;
        }

        if (count($errors) != 0) {
            $errorMessage = json_encode($errors, JSON_UNESCAPED_UNICODE);
            throw new ApiException(
                'Не верно заполнены поля шаблона:',
                'IncorrectTemplate',
                $errorMessage,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $fieldsTemplate;
    }

    /**
     * @param string $id
     * @return FormTemplate|object|null
     * @throws ApiException
     */
    public function cloneFormTemplate(string $id)
    {
        $formTemplate = $this->entityManager->getRepository(FormTemplate::class)->findOneBy(['id' => $id]);
        if (!$formTemplate) {
            throw new ApiException('FormTemplate not found', '', null, 400);
        }

        $formTemplateClone = clone $formTemplate;
        $formTemplates = $this->entityManager->getRepository(FormTemplate::class)->findBy(['parent' => $id]);
        if (empty($formTemplates)) {
            $formName = 'Копия ';
        } else {
            $formName = 'Копия('.count($formTemplates).') ';
        }
        $formTemplateClone->setName($formName . $formTemplate->getName());
        $formTemplateClone->setCode(null);
        $formTemplateClone->setParent($id);
        $formTemplateClone->setAppointmentCount(0);
        $formTemplateClone->setActive(true);
        $this->entityManager->persist($formTemplateClone);
        /** @var FormTemplateField $templateField */
        foreach ($formTemplate->getFormFields() as $templateField) {
            $cloneFormTemplateField = clone $templateField;
            $cloneFormTemplateField->setFormTemplate($formTemplateClone);
            $this->entityManager->persist($cloneFormTemplateField);
            /** @var FormFieldPropertyValue $property */
            foreach ($templateField->getProperties() as $property) {
                $cloneProperty = clone $property;
                $cloneProperty->setFormField($cloneFormTemplateField);
                $this->entityManager->persist($cloneProperty);
            }
        }
        $this->entityManager->flush();

        return $formTemplateClone;
    }
}
