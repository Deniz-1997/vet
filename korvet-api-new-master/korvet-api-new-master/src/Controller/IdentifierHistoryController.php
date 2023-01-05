<?php

namespace App\Controller;

use App\Entity\Pet\IdentifierHistory;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetListTrait;

/**
 * @Route("/api/pet/identifier-history")
 *
 * @Resource(
 *     tags={"Pet"},
 *     summariesMap={
 *          "list": "Получить список историй идентификаторов",
 *          "get": "Получить историю идентификаторов",
 *          "post": "Добавить историю идентификаторов",
 *          "delete": "Удалить историю идентификаторов",
 *          "patch": "Обновить историю идентификаторов"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список загруженных файлов",
 *          "get": "Получить историю идентификаторов",
 *          "post": "Добавить историю идентификаторов",
 *          "delete": "Удалить историю идентификаторов",
 *          "patch": "Обновить историю идентификаторов"
 *     }
 * )
 */
class IdentifierHistoryController extends ApiController
{
    use GetListTrait, AddItemTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = IdentifierHistory::class;
}
