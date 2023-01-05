<?php

namespace App\Controller\Reference\Owner;

use App\Entity\Reference\Owner\Activity as ActivityEntity;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class Activity
 * @package App\Controller\Reference\Owner
 * @Route("/api/reference/owner-activity")
 * @Resource(
 *     description="Main desc",
 *     tags={"OwnerActivity"},
 *     summariesMap={
 *          "list": "Получить список видов деятельности владельцев",
 *          "get": "Получить вид деятельности владельцев",
 *          "post": "Создать вид деятельности владельцев",
 *          "delete": "Удалить вид деятельности владельцев",
 *          "patch": "Обновить вид деятельности владельцев"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список видов деятельности владельцев",
 *          "get": "Возвращает вид деятельности владельцев",
 *          "post": "Создает новый вид деятельности владельцев",
 *          "delete": "Удаляет существующий вид деятельности владельцев",
 *          "patch": "Обновляет существующий вид деятельности владельцев"
 *     }
 * )
 */
class Activity extends AbstractController
{
    public const ENTITY_CLASS = ActivityEntity::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
