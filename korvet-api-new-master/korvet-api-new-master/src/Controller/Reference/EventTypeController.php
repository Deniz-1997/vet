<?php

namespace App\Controller\Reference;

use App\Entity\Reference\EventType;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EventTypeController
 * @package App\Controller\Reference
 * @Route("/api/reference/event-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"EventType"},
 *     summariesMap={
 *          "list": "Получить список типов мероприятий",
 *          "get": "Получить тип мероприятия",
 *          "post": "Создать тип мероприятия",
 *          "delete": "Удалить тип мероприятия",
 *          "patch": "Обновить тип мероприятия"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов мероприятий",
 *          "get": "Возвращает тип мероприятия по идентификатору",
 *          "post": "Создает новый тип мероприятия",
 *          "delete": "Удаляет существующий тип мероприятия",
 *          "patch": "Обновляет существующий тип мероприятия"
 *     }
 * )
 */
class EventTypeController extends AbstractController
{
    public const ENTITY_CLASS = EventType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
