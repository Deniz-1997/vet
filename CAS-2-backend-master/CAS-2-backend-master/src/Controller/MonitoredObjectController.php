<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 24.08.17
 * Time: 3:47
 */

namespace App\Controller;

use App\Entity\Owner\MonitoredObject;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class MonitoredObjectController
 * @package App\Controller
 * @Route("/api/monitored-object")
 * @Resource(
 *     description="Main desc",
 *     tags={"MonitoredObject"},
 *     summariesMap={
 *          "list": "Получить список подконтрольных объектов",
 *          "get": "Получить подконтрольный объект",
 *          "post": "Создать подконтрольный объект",
 *          "delete": "Удалить подконтрольный объект",
 *          "patch": "Обновить подконтрольный объект"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список подконтрольных объектов",
 *          "get": "Возвращает подконтрольный объект по идентификатору",
 *          "post": "Создает новый подконтрольный объект",
 *          "delete": "Удаляет существующий подконтрольный животных",
 *          "patch": "Обновляет существующий подконтрольный животных"
 *     }
 * )
 */
class MonitoredObjectController extends Controller
{
    public const ENTITY_CLASS = MonitoredObject::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
