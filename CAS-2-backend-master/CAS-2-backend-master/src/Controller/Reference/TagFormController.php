<?php

namespace App\Controller\Reference;

use App\Entity\Reference\TagForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class TagFormController
 * @Route("/api/reference/tag-form")
 * @Resource(
 *     tags={"TagForm"},
 *     summariesMap={
 *          "list": "Получить список форм для бирок",
 *          "get": "Получить форму для бирки",
 *          "post": "Создать форму для бирки",
 *          "delete": "Удалить форму для бирки",
 *          "patch": "Обновить форму для бирки"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список форм для бирок",
 *          "get": "Получить форму для бирки",
 *          "post": "Создать форму для бирки",
 *          "delete": "Удалить форму для бирки",
 *          "patch": "Обновить форму для бирки"
 *     },
 * )
 */

class TagFormController extends AbstractController
{
    public const ENTITY_CLASS = TagForm::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

}
