<?php

namespace App\Controller;

use App\Entity\ProductStock;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductStockController
 * @package App\Controller
 * @Route("/api/product-stock")
 * @Resource(
 *     description="Main desc",
 *     tags={"ProductStock"},
 *     summariesMap={
 *          "list": "Получить список остатков товаров",
 *          "get": "Получить остаток товара",
 *          "post": "Создать остаток товара",
 *          "delete": "Удалить остаток товара",
 *          "patch": "Обновить остаток товара"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список остатков товаров",
 *          "get": "Возвращает остаток товара по идентификатору",
 *          "post": "Создает новый остаток товара",
 *          "delete": "Удаляет существующий остаток товара",
 *          "patch": "Обновляет существующий остаток товара"
 *     }
 * )
 */
class ProductStockController extends ApiController
{
    public const ENTITY_CLASS = ProductStock::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

}
