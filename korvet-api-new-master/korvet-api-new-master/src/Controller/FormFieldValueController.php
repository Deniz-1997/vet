<?php


namespace App\Controller;

use App\Entity\Form\FormFieldValue;
use App\Controller\ApiController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};

/**
 * Class FormFieldValueController
 * @package App\Controller
 * @Route("/api/form-field-value")
 * @Resource(
 *     description="Main desc",
 *     tags={"FormFieldValue"},
 *     summariesMap={
 *          "list": "Получить список полей формы в приеме",
 *          "get": "Получить поле формы в приеме",
 *          "post": "Создать поле формы в приеме",
 *          "delete": "Удалить поле формы в приеме",
 *          "patch": "Обновить поле формы в приеме"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список полей формы в приеме",
 *          "get": "Возвращает поле формы в приеме по идентификатору",
 *          "post": "Создает новое поле формы в приеме",
 *          "delete": "Удаляет существующее поле формы в приеме",
 *          "patch": "Обновляет существующее поле формы в приеме"
 *     }
 * )
 */
class FormFieldValueController extends ApiController
{
    public const ENTITY_CLASS = FormFieldValue::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
