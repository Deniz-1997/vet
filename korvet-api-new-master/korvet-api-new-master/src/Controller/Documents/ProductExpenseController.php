<?php


namespace App\Controller\Documents;

use App\Entity\Document\ProductExpense;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class ProductExpenseController
 * @package App\Controller\Documents
 * @Route("/api/document/product-expense")
 * @Resource(
 *     description="Main desc",
 *     tags={"ProductExpense"},
 *     summariesMap={
 *          "list": "Получить список списаной номенклатуры",
 *          "get": "Получить списание номенклатуры",
 *          "post": "Создать списание номенклатуры",
 *          "delete": "Удалить списание номенклатуры",
 *          "patch": "Обновить списание номенклатуры"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список списаной номенклатуры",
 *          "get": "Возвращает списание номенклатуры по идентификатору",
 *          "post": "Создает новое списание номенклатуры",
 *          "delete": "Удаляет существующее списание номенклатуры",
 *          "patch": "Обновляет существующее списание номенклатуры"
 *     }
 * )
 */
class ProductExpenseController extends ApiController
{
    public const ENTITY_CLASS = ProductExpense::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;
}
