<?php


namespace App\Controller\Documents;

use App\Entity\Document\DocumentProduct;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class DocumentProductController
 * @package App\Controller\Documents
 * @Route("/api/document/product")
 * @Resource(
 *     description="Main desc",
 *     tags={"DocumentProduct"},
 *     summariesMap={
 *          "list": "Получить список продуктДокумента",
 *          "get": "Получить продуктДокумент",
 *          "post": "Создать продуктДокумент",
 *          "delete": "Удалить продуктДокумент",
 *          "patch": "Обновить продуктДокумент"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список продуктДокумента",
 *          "get": "Возвращает продуктДокумент",
 *          "post": "Создает новый ппродуктДокумент",
 *          "delete": "Удаляет существующий продуктДокумент",
 *          "patch": "Обновляет существующий продуктДокумент"
 *     }
 * )
 */
class DocumentProductController extends ApiController
{
    public const ENTITY_CLASS = DocumentProduct::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
