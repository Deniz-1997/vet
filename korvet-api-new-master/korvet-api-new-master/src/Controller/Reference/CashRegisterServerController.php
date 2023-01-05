<?php

namespace App\Controller\Reference;

use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Reference\CashRegisterServer;

/**
 * Class CashRegisterServerController
 * @Route("/api/reference/cash-register-server")
 * @Resource(
 *     description="ККМ сервер",
 *     tags={"CashRegisterServer"},
 *     summariesMap={
 *          "list": "Получить список ККМ-серверов",
 *          "get": "Получить ККМ-сервер",
 *          "post": "Создать ККМ-сервер",
 *          "delete": "Удалить ККМ-сервер",
 *          "patch": "Обновить ККМ-сервер"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список ККМ-серверов",
 *          "get": "Возвращает ККМ-сервер по идентификатору",
 *          "post": "Создает новый ККМ-сервер",
 *          "delete": "Удаляет существующий ККМ-сервер",
 *          "patch": "Обновляет существующий ККМ-сервер"
 *     }
 * )
 */
class CashRegisterServerController extends AbstractController
{
    public const ENTITY_CLASS = CashRegisterServer::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
