<?php


namespace App\Controller;


use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Entity\Reference\Action;
use App\Entity\Reference\ActionGroup;

/**
 * Class ActionGroupController
 *
 * @Route("/api/action")
 * @Resource(
 *     description="Main desc",
 *     tags={"Action"},
 *     summariesMap={
 *          "list": "Получить список действий",
 *          "get": "Получить действие",
 *          "post": "Создать действие",
 *          "delete": "Удалить действие",
 *          "patch": "Обновить действие"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список действий",
 *          "get": "Возвращает действие по идентификатору",
 *          "post": "Создает новый действие",
 *          "delete": "Удаляет существующий действие",
 *          "patch": "Обновляет существующий действие"
 *     }
 * )
 */
class ActionController extends ApiController
{
    const ENTITY_CLASS = Action::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['default']],
            'addItem'    => ['groups' => ['default']],
            'getItem'    => ['groups' => ['default']],
            'patchItem'  => ['groups' => ['default']],
            'updateItem' => ['groups' => ['default']],
        ];
    }
}
