<?php

namespace App\Controller\Reference\Appointment;


use App\Entity\Reference\Appointment\AppointmentTemplate;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class AppointmentTemplateController
 * @package App\Controller\Reference
 * @Route("/api/reference/appointment-template")
 * @Resource(
 *     description="Main desc",
 *     tags={"AppointmentTemplate"},
 *     summariesMap={
 *          "list": "Получить список шаблонов приема",
 *          "get": "Получить шаблон приема",
 *          "post": "Создать шаблон приема",
 *          "delete": "Удалить шаблон приема",
 *          "patch": "Обновить шаблон приема"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список шаблонов приема",
 *          "get": "Возвращает шаблон приема по идентификатору",
 *          "post": "Создает новый шаблон приема",
 *          "delete": "Удаляет шаблон приема",
 *          "patch": "Обновляет шаблон приема"
 *     }
 * )
 */
class AppointmentTemplateController extends ApiController
{
    public const ENTITY_CLASS = AppointmentTemplate::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

}
