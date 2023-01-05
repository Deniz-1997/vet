<?php

namespace App\Controller;

use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Appointment\AppointmentType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AppointmentTypeController
 * @package App\Controller
 * @Route("/api/appointment-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"Appointment"},
 *     summariesMap={
 *          "list": "Получить список типов тип приемаов",
 *          "get": "Получить тип приема",
 *          "post": "Создать тип приема",
 *          "delete": "Удалить тип приема",
 *          "patch": "Обновить тип приема"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов тип приемаов",
 *          "get": "Возвращает тип приема по идентификатору",
 *          "post": "Создает новый тип приема",
 *          "delete": "Удаляет существующий тип приема",
 *          "patch": "Обновляет существующий тип приема"
 *     }
 * )
 */
class AppointmentTypeController extends AbstractController
{
    public const ENTITY_CLASS = AppointmentType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
