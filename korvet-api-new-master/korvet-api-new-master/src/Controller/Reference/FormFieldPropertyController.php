<?php

namespace App\Controller\Reference;

use App\Entity\Reference\FormFieldProperty;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};
use App\Controller\ApiController;

/**
 * Class FormFieldPropertyController
 * @package App\Controller\Reference
 * @Route("/api/reference/form-field-property")
 * @Resource(
 *     description="Main desc",
 *     tags={"FormFieldProperty"},
 *     summariesMap={
 *          "list": "Получить список свойств для полей",
 *          "get": "Получить свойство поля",
 *          "post": "Создать свойство поля",
 *          "delete": "Удалить свойство поля",
 *          "patch": "Обновить свойство поля"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список свойств для полей",
 *          "get": "Возвращает свойство поля по идентификатору",
 *          "post": "Создает новое свойство для поля",
 *          "delete": "Удаляет существующее свойство поля",
 *          "patch": "Обновляет существующее свойство поля"
 *     }
 * )
 */
class FormFieldPropertyController extends ApiController
{
    public const ENTITY_CLASS = FormFieldProperty::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
