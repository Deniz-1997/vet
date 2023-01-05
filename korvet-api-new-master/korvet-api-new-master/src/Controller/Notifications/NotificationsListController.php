<?php


namespace App\Controller\Notifications;


use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Notifications\NotificationsList;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Entity\Reference\Action;
use App\Entity\Reference\ActionGroup;

/**
 * Class NotificationsListController
 *
 * @Route("/api/notifications")
 * @Resource(
 *     description="Main desc",
 *     tags={"Action"},
 *     summariesMap={
 *          "list": "Получить список уведомлений",
 *          "get": "Получить уведомление",
 *          "post": "Создать уведомление",
 *          "delete": "Удалить уведомление",
 *          "patch": "Обновить уведомление"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список уведомлений",
 *          "get": "Возвращает уведомление по идентификатору",
 *          "post": "Создает новый уведомление",
 *          "delete": "Удаляет существующий уведомление",
 *          "patch": "Обновляет существующий уведомление"
 *     }
 * )
 */
class NotificationsListController extends ApiController
{
    const ENTITY_CLASS = NotificationsList::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
