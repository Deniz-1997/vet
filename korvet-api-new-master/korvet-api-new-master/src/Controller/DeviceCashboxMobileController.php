<?php


namespace App\Controller;

use App\Entity\DeviceCashboxMobile;
use App\Controller\ApiController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};


/**
 * Class DeviceCashboxMobileController
 * @package App\Controller
 * @Route("/api/device-cashbox-mobile")
 * @Resource(
 *     description="Main desc",
 *     tags={"DeviceCashboxMobile"},
 *     summariesMap={
 *          "list": "Получить список терминалов оплат",
 *          "get": "Получить терминало оплат",
 *          "post": "Создать терминало оплат",
 *          "delete": "Удалить терминало оплат",
 *          "patch": "Обновить терминало оплат",
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список терминалов оплат",
 *          "get": "Возвращает терминало оплат по идентификатору",
 *          "post": "Создает новый терминал оплат",
 *          "delete": "Удаляет существующий терминал оплат",
 *          "patch": "Обновляет существующий терминал оплат",
 *     }
 * )
 */
class DeviceCashboxMobileController  extends ApiController
{
    public const ENTITY_CLASS = DeviceCashboxMobile::class;
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
