<?php


namespace App\Controller\Documents;

use App\Entity\Document\ProductTransfer;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;


/**
 * Class ProductTransferController
 * @package App\Controller\Documents
 * @Route("/api/document/product-transfer")
 * @Resource(
 *     description="Main desc",
 *     tags={"ProductTransfer"},
 *     summariesMap={
 *          "list": "Получить список перемещений номенклатуры",
 *          "get": "Получить перемещение номенклатуры",
 *          "post": "Создать перемещение номенклатуры",
 *          "delete": "Удалить перемещение номенклатуры",
 *          "patch": "Обновить перемещение номенклатуры"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список перемещений номенклатуры",
 *          "get": "Возвращает перемещение номенклатуры по идентификатору",
 *          "post": "Создает новое перемещение номенклатуры",
 *          "delete": "Удаляет существующее перемещение номенклатуры",
 *          "patch": "Обновляет существующее перемещение номенклатуры"
 *     }
 * )
 */
class ProductTransferController extends ApiController
{
    public const ENTITY_CLASS = ProductTransfer::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;
}
