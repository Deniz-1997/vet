<?php

namespace App\Controller\Reference;

use App\Entity\Reference\TagColor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class TagColorController
 * @Route("/api/reference/tag-color")
 * @Resource(
 *     tags={"TagColor"},
 *     summariesMap={
 *          "list": "Получить список цветов для бирок",
 *          "get": "Получить цвет для бирки",
 *          "post": "Создать цвет для бирки",
 *          "delete": "Удалить цвет для бирки",
 *          "patch": "Обновить цвет для бирки"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список цветов для бирок",
 *          "get": "Получить цвет для бирки",
 *          "post": "Создать цвет для бирки",
 *          "delete": "Удалить цвет для бирки",
 *          "patch": "Обновить цвет для бирки"
 *     },
 * )
 */
class TagColorController extends AbstractController
{
    public const ENTITY_CLASS = TagColor::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
