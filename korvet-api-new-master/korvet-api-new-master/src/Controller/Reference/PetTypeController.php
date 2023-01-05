<?php

namespace App\Controller\Reference;

use App\Entity\Reference\PetType;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PetTypeController
 * @package App\Controller\Reference
 * @Route("/api/reference/pet-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"PetType"},
 *     summariesMap={
 *          "list": "Получить список типов животных",
 *          "get": "Получить тип животного",
 *          "post": "Создать тип животного",
 *          "delete": "Удалить тип животного",
 *          "patch": "Обновить тип животного"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов животных",
 *          "get": "Возвращает тип животного по идентификатору",
 *          "post": "Создает новый тип животного",
 *          "delete": "Удаляет существующий тип животного",
 *          "patch": "Обновляет существующий тип животного"
 *     }
 * )
 */
class PetTypeController extends AbstractController
{
    public const ENTITY_CLASS = PetType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
