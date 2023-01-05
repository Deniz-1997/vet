<?php

namespace App\Controller\Reference;

use App\Entity\Reference\Breed;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BreedController
 * @package App\Controller\Reference
 * @Route("/api/reference/breed")
 * @Resource(
 *     description="Main desc",
 *     tags={"Breed"},
 *     summariesMap={
 *          "list": "Получить список пород животных",
 *          "get": "Получить породу животного",
 *          "post": "Создать породу животного",
 *          "delete": "Удалить породу животного",
 *          "patch": "Обновить породу животного"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список пород животных",
 *          "get": "Возвращает породу животного по идентификатору",
 *          "post": "Создает новую породу животного",
 *          "delete": "Удаляет существующую породу животного",
 *          "patch": "Обновляет существующую породу животного"
 *     }
 * )
 */
class BreedController extends AbstractController
{
    public const ENTITY_CLASS = Breed::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
