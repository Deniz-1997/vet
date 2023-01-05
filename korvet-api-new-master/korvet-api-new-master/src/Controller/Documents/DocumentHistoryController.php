<?php


namespace App\Controller\Documents;

use App\Entity\Document\DocumentHistory;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class DocumentHistoryController
 * @package App\Controller\Documents
 * @Route("/api/document/history")
 * @Resource(
 *     description="Main desc",
 *     tags={"DocumentHistory"},
 *     summariesMap={
 *          "list": "Получить список истории документов",
 *          "get": "Получить историю документов",
 *          "post": "Создать историю документов",
 *          "delete": "Удалить историю документов",
 *          "patch": "Обновить историю документов"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список истории документов",
 *          "get": "Возвращает историю документов",
 *          "post": "Создает историю документов",
 *          "delete": "Удаляет историю документов",
 *          "patch": "Обновляет существующую историю документов"
 *     }
 * )
 */
class DocumentHistoryController extends ApiController
{
    public const ENTITY_CLASS = DocumentHistory::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
