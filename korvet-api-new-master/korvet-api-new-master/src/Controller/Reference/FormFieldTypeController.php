<?php

namespace App\Controller\Reference;

use App\Entity\Reference\FormFieldType;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};
use App\Controller\ApiController;

/**
 * Class FormFieldTypeController
 * @package App\Controller\Reference
 * @Route("/api/reference/form-field-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"FormFieldType"},
 *     summariesMap={
 *          "list": "Получить список типов полей",
 *          "get": "Получить тип поля",
 *          "post": "Создать тип поля",
 *          "delete": "Удалить тип поля",
 *          "patch": "Обновить тип поля"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов полей",
 *          "get": "Возвращает тип поля по идентификатору",
 *          "post": "Создает новый тип поля",
 *          "delete": "Удаляет существующий тип поля",
 *          "patch": "Обновляет существующий тип поля"
 *     }
 * )
 */
class FormFieldTypeController extends ApiController
{
    public const ENTITY_CLASS = FormFieldType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
