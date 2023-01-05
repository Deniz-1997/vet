<?php

namespace App\Controller;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Entity\Reference\Icon;

/**
 * Class IconController
 *
 * @Route("/api/icon")
 * @Resource(
 *     description="Main desc",
 *     tags={"Icon"},
 *     summariesMap={
 *          "list": "Получить список иконок",
 *          "get": "Получить иконку",
 *          "post": "Создать иконку",
 *          "delete": "Удалить иконку",
 *          "patch": "Обновить иконку"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список иконок",
 *          "get": "Возвращает иконку по идентификатору",
 *          "post": "Создает новый иконку",
 *          "delete": "Удаляет существующий иконку",
 *          "patch": "Обновляет существующий иконку"
 *     }
 * )
 */
class IconController extends Controller
{
    const ENTITY_CLASS = Icon::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
