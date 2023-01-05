<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\Probe;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProbeController
 * @Route("/api/laboratory/probe")
 * @Resource(
 *     description="Main desc",
 *     tags={"Probe"},
 *     summariesMap={
 *          "list": "Получить список проб",
 *          "get": "Получить пробу",
 *          "post": "Создать пробу",
 *          "delete": "Удалить пробу",
 *          "patch": "Обновить пробу"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список проб",
 *          "get": "Возвращает пробу",
 *          "post": "Создает пробу",
 *          "delete": "Удаляет существующую пробу",
 *          "patch": "Обновляет существующую пробу"
 *     }
 * )
 */
class ProbeController extends AbstractController
{
    public const ENTITY_CLASS = Probe::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
