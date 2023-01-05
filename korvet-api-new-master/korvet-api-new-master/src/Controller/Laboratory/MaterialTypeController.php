<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\MaterialType;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class MaterialTypeController
 * @Route("/api/laboratory/material-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"MaterialType"},
 *     summariesMap={
 *          "list": "Получить список материалов",
 *          "get": "Получить материал",
 *          "post": "Создать материал",
 *          "delete": "Удалить материал",
 *          "patch": "Обновить материал"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список материалов",
 *          "get": "Возвращает материал",
 *          "post": "Создает материал",
 *          "delete": "Удаляет существующую материал",
 *          "patch": "Обновляет существующую материал"
 *     }
 * )
 */
class MaterialTypeController extends AbstractController
{
    public const ENTITY_CLASS = MaterialType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
