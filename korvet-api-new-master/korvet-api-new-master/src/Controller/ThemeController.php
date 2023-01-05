<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Packages\Annotation\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CRUD\{
    AddItemTrait, GetItemTrait, GetListTrait, UpdateItemTrait, DeleteItemTrait
};

/**
 * @Route("/api/theme")
 * @Resource(
 *     description="Main desc",
 *     tags={"Email Theme"},
 *     summariesMap={
 *          "list": "Получить список тем оформления",
 *          "get": "Получить тему оформления",
 *          "post": "Создать тему оформления",
 *          "delete": "Удалить тему оформления",
 *          "put": "Обновить тему оформления"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список тем оформления",
 *          "get": "Возвращает тему оформления по идентификатору",
 *          "post": "Создает новую тему оформления",
 *          "delete": "Удаляет существующую тему оформления",
 *          "put": "Обновляет существующую тему оформления"
 *     }
 * )
 */
class ThemeController extends AbstractController
{
    public const ENTITY_CLASS = Theme::class;

    use AddItemTrait, GetItemTrait, GetListTrait, UpdateItemTrait, DeleteItemTrait;
}
