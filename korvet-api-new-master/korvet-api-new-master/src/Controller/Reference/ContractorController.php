<?php

namespace App\Controller\Reference;

use \App\Entity\Contractor;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class ContractorController
 * @package App\Controller\Reference
 * @Route("/api/reference/contractor")
 * @Resource(
 *     description="Main desc",
 *     tags={"Contractor"},
 *     summariesMap={
 *          "list": "Получить список контрагентов",
 *          "get": "Получить контрагента",
 *          "post": "Создать контрагента",
 *          "delete": "Удалить контрагента",
 *          "patch": "Обновить контрагента"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список контрагентов",
 *          "get": "Возвращает контрагента по идентификатору",
 *          "post": "Создает нового контрагента",
 *          "delete": "Удаляет контрагента",
 *          "patch": "Обновляет контрагента"
 *     }
 * )
 */
class ContractorController extends ApiController
{
    public const ENTITY_CLASS = Contractor::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['api.contractor']],
            'addItem'    => ['groups' => ['api.contractor']],
            'getItem'    => ['groups' => ['api.contractor']],
            'patchItem'  => ['groups' => ['api.contractor']],
            'updateItem' => ['groups' => ['api.contractor']],
        ];
    }
}
