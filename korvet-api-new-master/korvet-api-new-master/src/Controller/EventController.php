<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 24.08.17
 * Time: 3:47
 */

namespace App\Controller;

use App\Entity\Event;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EventController
 * @package App\Controller
 * @Route("/api/event")
 * @Resource(
 *     description="Main desc",
 *     tags={"Event"},
 *     summariesMap={
 *          "list": "Получить список мероприятий",
 *          "get": "Получить мероприятие",
 *          "post": "Создать мероприятие",
 *          "delete": "Удалить мероприятие",
 *          "patch": "Обновить мероприятие"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список мероприятий",
 *          "get": "Возвращает мероприятие по идентификатору",
 *          "post": "Создает новое мероприятие",
 *          "delete": "Удаляет существующее мероприятие",
 *          "patch": "Обновляет существующее мероприятие"
 *     }
 * )
 */
class EventController extends AbstractController
{
    public const ENTITY_CLASS = Event::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
