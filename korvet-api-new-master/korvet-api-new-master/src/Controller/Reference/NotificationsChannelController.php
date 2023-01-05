<?php

namespace App\Controller\Reference;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Reference\Notifications\ReferenceNotificationsChannel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;


/**
 * Class NotificationsChannelController
 * @package App\Controller\Reference
 * @Route("/api/reference/notifications-channel")
 * @Resource(
 *     description="",
 *     tags={"ReferenceNotificationsChannel"},
 *     summariesMap={
 *          "list": "Получить список каналов для оповещений",
 *          "get": "Получить канал оповащения",
 *          "post": "Создать канал оповащения",
 *          "delete": "Удалить канал оповащения",
 *          "patch": "Обновить канал оповащения"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список каналов для оповещений",
 *          "get": "Возвращает канал оповащения по идентификатору",
 *          "post": "Создает новый канал для оповащения",
 *          "delete": "Удаляет существующее канал",
 *          "patch": "Обновляет существующее канал"
 *     }
 * )
 */
class NotificationsChannelController extends AbstractController
{
    public const ENTITY_CLASS = ReferenceNotificationsChannel::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
