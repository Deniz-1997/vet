<?php


namespace App\Controller;

use App\Entity\Form\FormField;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class FormTemplateFieldController
 * @package App\Controller
 * @Route("/api/form-field")
 * @Resource(
 *     description="Main desc",
 *     tags={"FormField"},
 *     summariesMap={
 *          "list": "Получить список полей",
 *          "get": "Получить поле",
 *          "post": "Создать поле",
 *          "delete": "Удалить поле",
 *          "patch": "Обновить поле"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список полей",
 *          "get": "Возвращает поле по идентификатору",
 *          "post": "Создает новое поле",
 *          "delete": "Удаляет существующее поле",
 *          "patch": "Обновляет существующее поле"
 *     }
 * )
 */
class FormFieldController extends ApiController
{
    public const ENTITY_CLASS = FormField::class;

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
