<?php

namespace App\Controller\Reference;

use App\Entity\Reference\Profession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfessionController
 * @package App\Controller
 * @Route("/api/reference/profession")
 * @Resource(
 *     description="Main desc",
 *     tags={"Profession"},
 *     summariesMap={
 *          "list": "Получить список cпециальностей",
 *          "get": "Получить cпециальность",
 *          "post": "Создать cпециальность",
 *          "delete": "Удалить cпециальность",
 *          "patch": "Обновить cпециальность"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список специальностей",
 *          "get": "Возвращает cпециальность по идентификатору",
 *          "post": "Создает новую cпециальность",
 *          "delete": "Удаляет cпециальность",
 *          "patch": "Обновляет cпециальность"
 *     }
 * )
 */
class ProfessionController extends AbstractController
{
    public const ENTITY_CLASS = Profession::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
