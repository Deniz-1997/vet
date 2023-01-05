<?php

namespace App\Controller;

use App\Entity\Owner\FileMonitoredObject;
use App\Entity\Reference\Owner\Activity as ActivityEntity;
use App\Entity\Owner\FileOwner;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class MonitoredObjectOwnerController
 *
 * @Route("/api/monitored-object-file")
 * @Resource(
 *     description="Main desc",
 *     tags={"MonitoredObject"},
 *     summariesMap={
 *          "list": "Получить список файлов подконтрольных объектов",
 *          "get": "Получить файл подконтрольного объекта",
 *          "post": "Создать файл подконтрольного объекта",
 *          "delete": "Удалить файл подконтрольного объекта",
 *          "patch": "Обновить файл подконтрольного объекта"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список файлов подконтрольных объектов",
 *          "get": "Возвращает файл подконтрольного объекта",
 *          "post": "Создает новый файл подконтрольного объекта",
 *          "delete": "Удаляет существующий файл подконтрольного объекта",
 *          "patch": "Обновляет существующий файл подконтрольного объекта"
 *     }
 * )
 */
class MonitoredObjectOwnerController extends AbstractController
{
    public const ENTITY_CLASS = FileMonitoredObject::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
