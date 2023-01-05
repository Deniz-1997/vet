<?php

namespace App\Controller\Pet;

use App\Entity\Pet\Identifier;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class IdentifierController
 * @package App\Controller\Pet
 * @Route("/api/pet/{petId}/identifier")
 * @Resource(
 *     description="Main desc",
 *     tags={"Pet"},
 *     summariesMap={
 *          "list": "Получить список идентификаторов животного",
 *          "get": "Получить идентификатор животного",
 *          "post": "Создать идентификатор животного",
 *          "delete": "Удалить идентификатор животного",
 *          "patch": "Обновить идентификатор животного"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список идентификаторов животного",
 *          "get": "Возвращает идентификатор животного",
 *          "post": "Создает новый идентификатор животного",
 *          "delete": "Удаляет существующий идентификатор животного",
 *          "patch": "Обновляет существующий идентификатор животного"
 *     }
 * )
 */
class IdentifierController extends AbstractController
{
    public const ENTITY_CLASS = Identifier::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
