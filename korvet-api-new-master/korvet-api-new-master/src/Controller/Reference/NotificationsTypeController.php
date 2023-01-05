<?php

namespace App\Controller\Reference;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Reference\Notifications\ReferenceNotificationsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class NotificationsTypeController
 * @package App\Controller\Reference
 * @Route("/api/reference/notifications-type")
 * @Resource(
 *     description="",
 *     tags={"ReferenceNotificationsType"},
 *     summariesMap={
 *          "list": "Получить список типов для оповещений",
 *          "get": "Получить тип оповащения",
 *          "post": "Создать тип оповащения",
 *          "delete": "Удалить тип оповащения",
 *          "patch": "Обновить тип оповащения"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов для оповещений",
 *          "get": "Возвращает тип оповащения по идентификатору",
 *          "post": "Создает новый тип для оповащения",
 *          "delete": "Удаляет существующее тип",
 *          "patch": "Обновляет существующее тип"
 *     }
 * )
 */
class NotificationsTypeController extends AbstractController
{
    public const ENTITY_CLASS = ReferenceNotificationsType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
