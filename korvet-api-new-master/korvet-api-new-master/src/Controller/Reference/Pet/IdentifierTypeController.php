<?php

namespace App\Controller\Reference\Pet;


use App\Entity\Reference\Pet\IdentifierType;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class IdentifierTypeController
 * @package App\Controller\Reference\Pet
 * @Route("/api/reference/pet-identifier-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"PetIdentifierType"},
 *     summariesMap={
 *          "list": "Получить список типов идентификаторов животных",
 *          "get": "Получить тип идентификаторов животных",
 *          "post": "Создать тип идентификаторов животных",
 *          "delete": "Удалить тип идентификаторов животных",
 *          "patch": "Обновить тип идентификаторов животных"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов идентификаторов животного",
 *          "get": "Возвращает тип идентификаторов животных",
 *          "post": "Создает новый тип идентификаторов животных",
 *          "delete": "Удаляет существующий тип идентификаторов животных",
 *          "patch": "Обновляет существующий тип идентификаторов животных"
 *     }
 * )
 */
class IdentifierTypeController extends AbstractController
{
    public const ENTITY_CLASS = IdentifierType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
