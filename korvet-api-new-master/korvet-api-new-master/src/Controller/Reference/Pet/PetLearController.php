<?php

namespace App\Controller\Reference\Pet;

use App\Entity\Reference\Pet\IdentifierType;
use App\Entity\Reference\Pet\PetLear;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PetLearController
 * @Route("/api/reference/pet-lear")
 * @Resource(
 *     description="Main desc",
 *     tags={"PetLear"},
 *     summariesMap={
 *          "list": "Получить список мастей животных",
 *          "get": "Получить масть животных",
 *          "post": "Создать масть животных",
 *          "delete": "Удалить масть животных",
 *          "patch": "Обновить масть животных"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список мастей животных",
 *          "get": "Получить масть животных",
 *          "post": "Создать масть животных",
 *          "delete": "Удалить масть животных",
 *          "patch": "Обновить масть животных"
 *     }
 * )
 */
class PetLearController extends AbstractController
{
    public const ENTITY_CLASS = PetLear::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
