<?php

namespace App\Controller;

use App\Entity\Appointment\AppointmentFormTemplate;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};
use App\Controller\ApiController;

/**
 * Class AppointmentFormTemplateController
 * @package App\Controller
 * @Route("/api/appointment-form-template")
 * @Resource(
 *     description="Main desc",
 *     tags={"AppointmentFormTemplate"},
 *     summariesMap={
 *          "list": "Получить список форм шаблона в приемах",
 *          "get": "Получить форму шаблона в приеме",
 *          "post": "Создать форму шаблона в приеме",
 *          "delete": "Удалить форму шаблона в приеме",
 *          "patch": "Обновить форму шаблона в приеме"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список форм шаблонов в приемах",
 *          "get": "Возвращает поле шаблона по идентификатору",
 *          "post": "Создает новое поле шаблона",
 *          "delete": "Удаляет существующее поле шаблона",
 *          "patch": "Обновляет существующее поле шаблона"
 *     }
 * )
 */
class AppointmentFormTemplateController extends ApiController
{
    public const ENTITY_CLASS = AppointmentFormTemplate::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
