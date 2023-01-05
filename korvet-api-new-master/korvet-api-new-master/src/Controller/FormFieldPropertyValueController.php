<?php


namespace App\Controller;

use App\Entity\Form\FormFieldPropertyValue;
use App\Controller\ApiController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};

/**
 * Class FormFieldPropertyValueController
 * @package App\Controller
 * @Route("/api/form-field-property-value")
 * @Resource(
 *     description="Main desc",
 *     tags={"FormFieldPropertyValue"},
 *     summariesMap={
 *          "list": "Получить список значений свойств поля",
 *          "get": "Получить значение свойства поля",
 *          "post": "Создать значение свойства поля",
 *          "delete": "Удалить значение свойства поля",
 *          "patch": "Обновить значение свойства поля"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список значений свойств поля",
 *          "get": "Возвращает значение свойства поля по идентификатору",
 *          "post": "Создает новое значение свойства поля",
 *          "delete": "Удаляет существующее значение свойства поля",
 *          "patch": "Обновляет существующее значение свойства поля"
 *     }
 * )
 */
class FormFieldPropertyValueController extends ApiController
{
    public const ENTITY_CLASS = FormFieldPropertyValue::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
