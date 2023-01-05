<?php

namespace App\Controller\Reference;

use App\Entity\Reference\AnimalDeath;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnimalDeathController
 * @package App\Controller\Reference
 * @Route("/api/reference/animal-death")
 * @Resource(
 *     description="Main desc",
 *     tags={"AnimalDeath"},
 *     summariesMap={
 *          "list": "Получить список смертей животных",
 *          "get": "Получить смерть животного",
 *          "post": "Создать смерть животного",
 *          "delete": "Удалить смерть животного",
 *          "patch": "Обновить смерть животного"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список смертей животных",
 *          "get": "Возвращает смерть животного по идентификатору",
 *          "post": "Создает новую смерть животного",
 *          "delete": "Удаляет существующую смерть животного",
 *          "patch": "Обновляет существующую смерть животного"
 *     }
 * )
 */
class AnimalDeathController extends AbstractController
{
    public const ENTITY_CLASS = AnimalDeath::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
