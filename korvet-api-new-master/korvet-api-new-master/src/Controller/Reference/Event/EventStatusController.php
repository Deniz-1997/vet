<?php

namespace App\Controller\Reference\Event;

use App\Entity\Reference\Appointment\AppointmentStatus;
use App\Entity\Reference\Breed;
use App\Entity\Reference\Event\EventStatus;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EventStatusController
 * @package App\Controller\Reference
 * @Route("/api/reference/event-status")
 * @Resource(
 *     description="Main desc",
 *     tags={"EventStatus"},
 *     summariesMap={
 *          "list": "Получить список статусов событий",
 *          "get": "Получить статус событий",
 *          "post": "Создать статус событий",
 *          "delete": "Удалить статус событий",
 *          "patch": "Обновить статус событий"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список статусов событий",
 *          "get": "Возвращает статус событий по идентификатору",
 *          "post": "Создает новый статус событий",
 *          "delete": "Удаляет существующую статус событий",
 *          "patch": "Обновляет существующую статус событий"
 *     }
 * )
 */
class EventStatusController extends AbstractController
{
    public const ENTITY_CLASS = EventStatus::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

