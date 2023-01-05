<?php

namespace App\Controller\Reference\Owner;

use App\Entity\Reference\Owner\Status;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class StatusController
 * @package App\Controller\Reference\Owner
 * @Route("/api/reference/owner-status")
 * @Resource(
 *     description="Main desc",
 *     tags={"OwnerStatus"},
 *     summariesMap={
 *          "list": "Получить список статусов владельцев",
 *          "get": "Получить статус владельца",
 *          "post": "Создать статус владельца",
 *          "delete": "Удалить статус владельца",
 *          "patch": "Обновить статус владельца"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список статусов владельцев",
 *          "get": "Возвращает статус владельца",
 *          "post": "Создает статус владельца",
 *          "delete": "Удаляет статус владельца",
 *          "patch": "Обновляет статус владельца"
 *     }
 * )
 */
class StatusController extends AbstractController
{
    public const ENTITY_CLASS = Status::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
