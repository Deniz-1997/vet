<?php

namespace App\Controller\Documents;

use App\Entity\Document\ProductInventory;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class ProductInventoryController
 * @package App\Controller\Documents
 * @Route("/api/document/product-inventory")
 * @Resource(
 *     description="Main desc",
 *     tags={"ProductInventory"},
 *     summariesMap={
 *          "list": "Получить список инвентаризаций номенклатуры",
 *          "get": "Получить инвентаризацию номенклатуры",
 *          "post": "Создать инвентаризацию номенклатуры",
 *          "delete": "Удалить инвентаризацию номенклатуры",
 *          "patch": "Обновить инвентаризацию номенклатуры"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список инвентаризаций номенклатуры",
 *          "get": "Возвращает инвентаризацию номенклатуры по идентификатору",
 *          "post": "Создает новую инвентаризацию номенклатуры",
 *          "delete": "Удаляет существующую инвентаризацию номенклатуры",
 *          "patch": "Обновляет существующую инвентаризацию номенклатуры"
 *     }
 * )
 */
class ProductInventoryController extends ApiController
{
    public const ENTITY_CLASS = ProductInventory::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;
}
