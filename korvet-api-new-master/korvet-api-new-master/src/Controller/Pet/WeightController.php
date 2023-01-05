<?php

namespace App\Controller\Pet;

use App\Entity\Pet\Weight;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class WeightController
 * @package App\Controller\Pet
 * @Route("/api/pet/weight")
 * @Resource(
 *     description="Main desc",
 *     tags={"Pet"},
 *     summariesMap={
 *          "list": "Получить список замеров веса животного",
 *          "get": "Получить замер веса животного",
 *          "post": "Создать замер веса животного",
 *          "delete": "Удалить замер веса животного",
 *          "patch": "Обновить замер веса животного"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список замеров веса животного",
 *          "get": "Возвращает замер веса животного",
 *          "post": "Создает новый замер веса животного",
 *          "delete": "Удаляет существующий замер веса животного",
 *          "patch": "Обновляет существующий замер веса животного"
 *     }
 * )
 */
class WeightController extends AbstractController
{
    public const ENTITY_CLASS = Weight::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}
