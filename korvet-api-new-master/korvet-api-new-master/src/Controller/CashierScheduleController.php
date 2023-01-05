<?php

namespace App\Controller;

use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Cash\CashierSchedule;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CashierScheduleController
 * @Route("/api/cashier-schedule")
 * @Resource(
 *     description="График работы кассиров",
 *     tags={"CashierSchedule"},
 *     summariesMap={
 *          "list": "Получить список графиков",
 *          "get": "Получить график",
 *          "post": "Создать график",
 *          "delete": "Удалить график",
 *          "patch": "Обновить график"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список графиков",
 *          "get": "Возвращает график по идентификатору",
 *          "post": "Создает новый график",
 *          "delete": "Удаляет существующий график",
 *          "patch": "Обновляет существуюищ график"
 *     }
 * )
 */
class CashierScheduleController extends AbstractController
{
    public const ENTITY_CLASS = CashierSchedule::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
