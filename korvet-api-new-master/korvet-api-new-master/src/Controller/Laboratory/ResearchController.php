<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\Research;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ResearchController
 * @Route("/api/laboratory/research")
 * @Resource(
 *     description="Main desc",
 *     tags={"Research"},
 *     summariesMap={
 *          "list": "Получить список исследований",
 *          "get": "Получить исследования",
 *          "post": "Создать исследования",
 *          "delete": "Удалить исследования",
 *          "patch": "Обновить исследования"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список исследований",
 *          "get": "Возвращает исследования",
 *          "post": "Создает исследования",
 *          "delete": "Удаляет существующую исследования",
 *          "patch": "Обновляет существующую исследования"
 *     }
 * )
 */
class ResearchController extends AbstractController
{
    public const ENTITY_CLASS = Research::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
