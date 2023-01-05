<?php

namespace App\Controller;

use App\Entity\Template;
use App\Packages\Annotation\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CRUD\{
    AddItemTrait, GetItemTrait, GetListTrait, UpdateItemTrait, DeleteItemTrait
};

/**
 * @Route("/api/template")
 * @Resource(
 *     description="Main desc",
 *     tags={"Email Template"},
 *     summariesMap={
 *          "list": "Получить список шаблонов email",
 *          "get": "Получить шаблон email",
 *          "post": "Создать шаблон email",
 *          "delete": "Удалить шаблон email",
 *          "put": "Обновить шаблон email"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список шаблонов email",
 *          "get": "Возвращает шаблон email по идентификатору",
 *          "post": "Создает новый шаблон email",
 *          "delete": "Удаляет существующий шаблон email",
 *          "put": "Обновляет существующий шаблон email"
 *     }
 * )
 */
class TemplateController extends AbstractController
{
    public const ENTITY_CLASS = Template::class;

    use AddItemTrait, GetItemTrait, GetListTrait, UpdateItemTrait, DeleteItemTrait;
}
