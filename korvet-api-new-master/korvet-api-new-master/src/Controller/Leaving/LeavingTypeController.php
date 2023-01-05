<?php

namespace App\Controller\Leaving;

use App\Entity\Leaving\LeavingType;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class LeavingTypeController
 * @package App\Controller
 * @Route("/api/leaving-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"Leaving"},
 *     summariesMap={
 *          "list": "Получить список типов тип выезда",
 *          "get": "Получить тип выезда",
 *          "post": "Создать тип выезда",
 *          "delete": "Удалить тип выезда",
 *          "patch": "Обновить тип выезда"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов тип выезда",
 *          "get": "Возвращает тип выезда по идентификатору",
 *          "post": "Создает новый тип выезда",
 *          "delete": "Удаляет существующий тип выезда",
 *          "patch": "Обновляет существующий тип выезда"
 *     }
 * )
 */
class LeavingTypeController extends AbstractController
{
    public const ENTITY_CLASS = LeavingType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
