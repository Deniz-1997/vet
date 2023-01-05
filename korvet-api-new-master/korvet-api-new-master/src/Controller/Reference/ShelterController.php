<?php

namespace App\Controller\Reference;

use App\Entity\Reference\Shelter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class ShelterController
 * @Route("/api/reference/shelter")
 * @Resource(
 *     tags={"Shelter"},
 *     summariesMap={
 *          "list": "Получить список приютов",
 *          "get": "Получить приют",
 *          "post": "Создать приют",
 *          "delete": "Удалить приют",
 *          "patch": "Обновить приют"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список приютов",
 *          "get": "Получить приют",
 *          "post": "Создать приют",
 *          "delete": "Удалить приют",
 *          "patch": "Обновить приют"
 *     },
 * )
 */
class ShelterController extends AbstractController
{
    public const ENTITY_CLASS = Shelter::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
