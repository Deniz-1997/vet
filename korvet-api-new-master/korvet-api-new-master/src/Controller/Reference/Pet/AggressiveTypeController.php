<?php

namespace App\Controller\Reference\Pet;

use App\Entity\Reference\Pet\AggressiveType;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AggressiveTypeController
 * @package App\Controller\Reference\Pet
 * @Route("/api/reference/pet-aggressive-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"PetAggressiveType"},
 *     summariesMap={
 *          "list": "Получить список степеней агрессивности животных",
 *          "get": "Получить степень агрессивности животных",
 *          "post": "Создать степень агрессивности животных",
 *          "delete": "Удалить степень агрессивности животных",
 *          "patch": "Обновить степень агрессивности животных"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов идентификаторов животного",
 *          "get": "Возвращает степень агрессивности животных",
 *          "post": "Создает степень агрессивности животных",
 *          "delete": "Удаляет существующую степень агрессивности животных",
 *          "patch": "Обновляет существующую степень агрессивности животных"
 *     }
 * )
 */
class AggressiveTypeController extends AbstractController
{
    public const ENTITY_CLASS = AggressiveType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
