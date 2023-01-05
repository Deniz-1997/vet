<?php

namespace App\Controller;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Entity\Reference\ActionGroup;

/**
 * Class ActionGroupController
 *
 * @Route("/api/action-group")
 * @Resource(
 *     description="Main desc",
 *     tags={"ActionGroup"},
 *     summariesMap={
 *          "list": "Получить список групп действий",
 *          "get": "Получить группу действий",
 *          "post": "Создать группу действий",
 *          "delete": "Удалить группу действий",
 *          "patch": "Обновить группу действий"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список групп действий",
 *          "get": "Возвращает группу действий по идентификатору",
 *          "post": "Создает новый группу действий",
 *          "delete": "Удаляет существующий группу действий",
 *          "patch": "Обновляет существующий группу действий"
 *     }
 * )
 */
class ActionGroupController extends ApiController
{
    const ENTITY_CLASS = ActionGroup::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['api.actionGroup']],
            'addItem'    => ['groups' => ['api.actionGroup']],
            'getItem'    => ['groups' => ['api.actionGroup']],
            'patchItem'  => ['groups' => ['api.actionGroup']],
            'updateItem' => ['groups' => ['api.actionGroup']],
        ];
    }
}
