<?php

namespace App\Controller\Reference\Appointment;

use App\Entity\Reference\Appointment\AppointmentStatus;
use App\Entity\Reference\Breed;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AppointmentStatusController
 * @package App\Controller\Reference
 * @Route("/api/reference/appointment-status")
 * @Resource(
 *     description="Main desc",
 *     tags={"AppointmentStatus"},
 *     summariesMap={
 *          "list": "Получить список статусов приема",
 *          "get": "Получить статус приема",
 *          "post": "Создать статус приема",
 *          "delete": "Удалить статус приема",
 *          "patch": "Обновить статус приема"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список статусов приема",
 *          "get": "Возвращает статус приема по идентификатору",
 *          "post": "Создает новый статус приема",
 *          "delete": "Удаляет существующий статус приема",
 *          "patch": "Обновляет существующий статус приема"
 *     }
 * )
 */
class AppointmentStatusController extends AbstractController
{
    public const ENTITY_CLASS = AppointmentStatus::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

