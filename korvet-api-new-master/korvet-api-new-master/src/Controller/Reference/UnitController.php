<?php

namespace App\Controller\Reference;

use App\Entity\Reference\Unit;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UnitController
 * @package App\Controller/Reference
 * @Route("/api/reference/unit")
 * @Resource(
 *     description="Main desc",
 *     tags={"Unit"},
 *     summariesMap={
 *          "list": "Получить список подразделений",
 *          "get": "Получить подразделение",
 *          "post": "Создать подразделение",
 *          "delete": "Удалить подразделение",
 *          "patch": "Обновить подразделение"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список подразделений",
 *          "get": "Возвращает подразделение по идентификатору",
 *          "post": "Создает новое подразделение",
 *          "delete": "Удаляет существующее подразделение",
 *          "patch": "Обновляет существующее подразделение"
 *     }
 * )
 */
class UnitController extends ApiController
{
    public const ENTITY_CLASS = Unit::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['default']],
            'addItem'    => ['groups' => ['api.unit']],
            'getItem'    => ['groups' => ['api.unit']],
            'patchItem'  => ['groups' => ['api.unit']],
            'updateItem' => ['groups' => ['api.unit']],
        ];
    }
}
