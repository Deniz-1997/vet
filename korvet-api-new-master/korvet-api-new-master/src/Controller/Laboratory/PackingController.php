<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\Packing;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PackingController
 * @Route("/api/laboratory/packing")
 * @Resource(
 *     description="Main desc",
 *     tags={"Packing"},
 *     summariesMap={
 *          "list": "Получить список упаковок",
 *          "get": "Получить упаковку",
 *          "post": "Создать упаковку",
 *          "delete": "Удалить упаковку",
 *          "patch": "Обновить упаковку"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список упаковок",
 *          "get": "Возвращает упаковку",
 *          "post": "Создает упаковку",
 *          "delete": "Удаляет существующую упаковку",
 *          "patch": "Обновляет существующую упаковку"
 *     }
 * )
 */
class PackingController extends AbstractController
{
    public const ENTITY_CLASS = Packing::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
