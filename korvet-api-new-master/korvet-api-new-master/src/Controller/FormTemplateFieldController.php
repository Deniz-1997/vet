<?php

namespace App\Controller;

use App\Entity\Form\FormTemplateField;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};
use App\Controller\ApiController;

/**
 * Class FormTemplateFieldController
 * @package App\Controller
 * @Route("/api/form-template-field")
 * @Resource(
 *     description="Main desc",
 *     tags={"FormTemplateField"},
 *     summariesMap={
 *          "list": "Получить список полей шаблона",
 *          "get": "Получить поле шаблона",
 *          "post": "Создать поле шаблона",
 *          "delete": "Удалить поле шаблона",
 *          "patch": "Обновить поле шаблона"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список полей шаблона",
 *          "get": "Возвращает поле шаблона по идентификатору",
 *          "post": "Создает новое поле шаблона",
 *          "delete": "Удаляет существующее поле шаблона",
 *          "patch": "Обновляет существующее поле шаблона"
 *     }
 * )
 */
class FormTemplateFieldController extends ApiController
{
    public const ENTITY_CLASS = FormTemplateField::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['api.formTemplate']],
            'addItem'    => ['groups' => ['api.formTemplate']],
            'getItem'    => ['groups' => ['api.formTemplate']],
            'patchItem'  => ['groups' => ['api.formTemplate']],
            'updateItem' => ['groups' => ['api.formTemplate']],
        ];
    }
}
