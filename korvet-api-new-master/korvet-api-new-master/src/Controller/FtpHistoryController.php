<?php


namespace App\Controller;

use App\Entity\FtpHistory;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class FtpHistory
 * @package App\Controller
 * @Route("/api/ftp-history")
 * @Resource(
 *     description="Main desc",
 *     tags={"FtpHistory"},
 *     summariesMap={
 *          "list": "Получить список истории импорта/экспорта отчетов 1С",
 *          "get": "Получить историю импорта/экспорта отчетов 1С",
 *          "post": "Создать историю импорта/экспорта отчетов 1С",
 *          "delete": "Удалить историю импорта/экспорта отчетов 1С",
 *          "patch": "Обновить историю импорта/экспорта отчетов 1С"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список истории импорта/экспорта отчетов 1С",
 *          "get": "Возвращает историю импорта/экспорта отчетов 1С по идентификатору",
 *          "post": "Создает новую историю импорта/экспорта отчетов 1С",
 *          "delete": "Удаляет существующую историю импорта/экспорта отчетов 1С",
 *          "patch": "Обновляет существующую историю импорта/экспорта отчетов 1С"
 *     }
 * )
 */
class FtpHistoryController extends ApiController
{
    public const ENTITY_CLASS = FtpHistory::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
