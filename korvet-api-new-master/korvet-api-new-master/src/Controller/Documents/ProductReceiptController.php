<?php


namespace App\Controller\Documents;

use App\Entity\Document\ProductReceipt;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class ProductReceiptController
 * @package App\Controller\Documents
 * @Route("/api/document/product-receipt")
 * @Resource(
 *     description="Main desc",
 *     tags={"ProductReceipt"},
 *     summariesMap={
 *          "list": "Получить список поступлений номенклатуры",
 *          "get": "Получить поступление номенклатуры",
 *          "post": "Создать поступление номенклатуры",
 *          "delete": "Удалить поступление номенклатуры",
 *          "patch": "Обновить поступление номенклатуры"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список поступлений номенклатуры",
 *          "get": "Возвращает поступление номенклатуры по идентификатору",
 *          "post": "Создает новое поступление номенклатуры",
 *          "delete": "Удаляет существующее поступление номенклатуры",
 *          "patch": "Обновляет существующее поступление номенклатуры"
 *     }
 * )
 */
class ProductReceiptController extends ApiController
{
    public const ENTITY_CLASS = ProductReceipt::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;
}
