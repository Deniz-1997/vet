<?php

namespace App\Controller;

use App\Entity\WildAnimal;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
/**
 * Class WildAnimalController
 * @package App\Controller
 * @Route("/api/wild-animal")
 * @Resource(
 *     tags={"WildAnimal"},
 *     summariesMap={
 *          "list": "Получить список диких животных",
 *          "get": "Получить дикое животное",
 *          "post": "Создать дикое животное",
 *          "delete": "Удалить дикое животное",
 *          "patch": "Обновить дикое животное"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список диких животных",
 *          "get": "Возвращает дикое животное по идентификатору",
 *          "post": "Создать дикое животное",
 *          "delete": "Удалить дикое животное",
 *          "patch": "Обновить дикое животное"
 *     },
 * )

 */


class WildAnimalController extends ApiController
{
    public const ENTITY_CLASS = WildAnimal::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['default']],
            'addItem'    => ['groups' => ['api.wildAnimal']],
            'getItem'    => ['groups' => ['api.wildAnimal']],
            'patchItem'  => ['groups' => ['api.wildAnimal']],
            'updateItem' => ['groups' => ['api.wildAnimal']],
        ];
    }
}
